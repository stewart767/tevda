<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\Member;
use App\Models\MembershipCard;
use App\Models\MembershipCategory;
use App\Models\MembershipDocument;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\AuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MemberController extends BaseApiController
{
    /**
     * Helper to resolve the authenticated member or fallback demo
     */
    protected function resolveMember(Request $request): ?Member
    {
        $user = $this->getAuthenticatedUser($request);
        if ($user) {
            $member = Member::with(['category', 'region', 'district', 'ward', 'vehicles', 'cards', 'documents', 'invoices.payments'])
                ->where('user_id', $user->id)
                ->first();
            if ($member) {
                return $member;
            }
        }

        // Fallback for unauthenticated read or demo
        return Member::with(['category', 'region', 'district', 'ward', 'vehicles', 'cards', 'documents'])
            ->first();
    }

    /**
     * Get member profile
     */
    public function profile(Request $request): JsonResponse
    {
        $member = $this->resolveMember($request);

        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        return $this->successResponse($member, 'Wasifu wa mwanachama.');
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $member = $this->resolveMember($request);
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $allowed = $request->only([
            'phone', 'email', 'physical_address', 'occupation', 'ev_sector',
            'employer', 'ev_experience_years', 'next_of_kin_name',
            'next_of_kin_relationship', 'next_of_kin_phone', 'ward_name'
        ]);

        $member->update($allowed);

        return $this->successResponse($member->fresh(['category', 'region', 'district']), 'Wasifu umesasishwa kikamilifu.');
    }

    /**
     * Phase 6: Application Tracking
     */
    public function applicationStatus(Request $request): JsonResponse
    {
        $user = $this->getAuthenticatedUser($request);
        if (!$user) {
            return $this->errorResponse('Tafadhali ingia kwanza kwenye akaunti yako.', 401);
        }

        $member = Member::with(['category', 'region', 'district', 'vehicles', 'documents', 'invoices.payments'])
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            return $this->successResponse([
                'has_application' => false,
                'status' => 'not_started',
                'title' => 'Haujawasilisha Maombi',
                'message' => 'Anza usajili wako rasmi wa uanachama wa TEVDA.',
                'progress_step' => 1,
            ], 'Hakuna maombi yaliyopatikana.');
        }

        $latestInvoice = $member->invoices ? $member->invoices->sortByDesc('id')->first() : null;
        $paymentStatus = $latestInvoice ? $latestInvoice->status : 'no_invoice';
        $controlNumber = $latestInvoice ? $latestInvoice->control_number : null;

        $history = [
            ['title' => 'Akaunti Iliundwa', 'date' => $user->created_at->format('d M Y, H:i'), 'status' => 'completed'],
            ['title' => 'Rasimu ya Maombi', 'date' => $member->created_at->format('d M Y, H:i'), 'status' => $member->status !== 'draft' ? 'completed' : 'in_progress'],
            ['title' => 'Maombi Yamewasilishwa', 'date' => $member->created_at->format('d M Y'), 'status' => in_array($member->status, ['submitted', 'payment_pending', 'payment_confirmed', 'under_review', 'approved']) ? 'completed' : 'pending'],
            ['title' => 'Ada & Stakabadhi ya Malipo', 'date' => $controlNumber ? "Control No: $controlNumber" : 'Inasubiriwa', 'status' => in_array($member->status, ['payment_confirmed', 'under_review', 'approved']) ? 'completed' : ($member->status === 'payment_pending' ? 'in_progress' : 'pending')],
            ['title' => 'Uhakiki wa Sekretarieti', 'date' => 'Kamati ya Uanachama', 'status' => in_array($member->status, ['approved']) ? 'completed' : ($member->status === 'under_review' ? 'in_progress' : 'pending')],
            ['title' => 'Kitambulisho cha Kidijitali Kiko Hai', 'date' => $member->status === 'approved' ? 'Umeidhinishwa' : 'Hatua ya Mwisho', 'status' => $member->status === 'approved' ? 'completed' : 'pending'],
        ];

        return $this->successResponse([
            'has_application' => true,
            'application_reference' => $member->membership_number ?: ('APP-' . date('Y') . '-' . str_pad($member->id, 5, '0', STR_PAD_LEFT)),
            'membership_number' => $member->membership_number,
            'submission_date' => $member->created_at->format('d M Y'),
            'category' => $member->category ? $member->category->name : 'Full Member',
            'category_id' => $member->category_id,
            'current_status' => $member->status,
            'payment_status' => $paymentStatus,
            'control_number' => $controlNumber,
            'invoice_amount' => $latestInvoice ? $latestInvoice->amount : 50000,
            'required_corrections' => $member->status === 'documents_incomplete' || $member->status === 'additional_info_required' ? $member->reviewer_notes : null,
            'administrative_messages' => $member->reviewer_notes ?: ($member->rejection_reason ?: 'Taarifa zako ziko salama na zinalindwa.'),
            'status_history' => $history,
            'documents_count' => $member->documents ? $member->documents->count() : 0,
            'vehicles_count' => $member->vehicles ? $member->vehicles->count() : 0,
        ], 'Hali ya maombi ya uanachama.');
    }

    /**
     * Phase 5: Get Saved Draft
     */
    public function getDraft(Request $request): JsonResponse
    {
        $user = $this->getAuthenticatedUser($request);
        if (!$user) {
            return $this->errorResponse('Tafadhali ingia kwanza.', 401);
        }

        $member = Member::with(['category', 'region', 'district', 'vehicles', 'documents'])
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            return $this->successResponse(null, 'Hakuna rasimu.');
        }

        return $this->successResponse([
            'member' => $member,
            'is_draft' => ($member->status === 'draft'),
            'status' => $member->status,
        ], 'Rasimu ya maombi.');
    }

    /**
     * Phase 5: Save Incomplete Application as Draft
     */
    public function saveDraft(Request $request): JsonResponse
    {
        $user = $this->getAuthenticatedUser($request);
        if (!$user) {
            return $this->errorResponse('Tafadhali ingia kwanza kwenye akaunti yako.', 401);
        }

        $member = Member::where('user_id', $user->id)->first();
        if (!$member) {
            $year = date('Y');
            $count = Member::whereYear('created_at', $year)->count() + 1;
            $appRef = sprintf('APP-%s-%05d', $year, $count);

            $member = new Member();
            $member->user_id = $user->id;
            $member->membership_number = $appRef;
            $member->full_name = $request->input('full_name') ?: $user->name;
            $member->phone = $user->phone;
            $member->email = $user->email;
            $member->status = 'draft';
        }

        // Fill draft fields if provided
        if ($request->filled('category_id')) $member->category_id = $request->input('category_id');
        if ($request->filled('full_name')) $member->full_name = $request->input('full_name');
        if ($request->filled('date_of_birth')) $member->date_of_birth = $request->input('date_of_birth');
        if ($request->filled('gender')) $member->gender = strtolower($request->input('gender'));
        if ($request->filled('region_id')) $member->region_id = $request->input('region_id');
        if ($request->filled('district_id')) $member->district_id = $request->input('district_id');
        if ($request->filled('ward_name')) $member->ward_name = $request->input('ward_name');
        if ($request->filled('physical_address')) $member->physical_address = $request->input('physical_address');
        if ($request->filled('nida_number')) $member->nida_number = $request->input('nida_number');
        if ($request->filled('driving_licence_number')) $member->driving_licence_number = $request->input('driving_licence_number');
        if ($request->filled('licence_class')) $member->licence_class = $request->input('licence_class');
        if ($request->filled('occupation')) $member->occupation = $request->input('occupation');
        if ($request->filled('ev_sector')) $member->ev_sector = $request->input('ev_sector');
        if ($request->filled('employer')) $member->employer = $request->input('employer');
        if ($request->filled('tin_number')) $member->tin_number = $request->input('tin_number');
        if ($request->filled('ev_experience_years')) $member->ev_experience_years = $request->input('ev_experience_years');

        $member->save();

        // Handle draft vehicle if submitted
        if ($request->filled('vehicle_registration')) {
            Vehicle::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'registration_number' => strtoupper(trim($request->input('vehicle_registration'))),
                ],
                [
                    'type' => $request->input('vehicle_type', 'two_wheeler'),
                    'make' => $request->input('vehicle_make', 'EV Maker'),
                    'model' => $request->input('vehicle_model', 'EV Model'),
                    'year' => $request->input('vehicle_year', date('Y')),
                    'ownership_type' => $request->input('ownership_type', 'owned'),
                    'battery_capacity_kwh' => $request->input('battery_capacity_kwh', 10.0),
                    'charging_type' => $request->input('charging_type', 'Battery Swap'),
                ]
            );
        }

        return $this->successResponse([
            'member' => $member->fresh(['category', 'vehicles', 'documents']),
            'message' => 'Rasimu ya maombi imehifadhiwa salama kwenye seva. Unaweza kuendelea wakati wowote.',
        ], 'Rasimu imehifadhiwa.');
    }

    /**
     * Phase 3: Review and Final Submit Membership Application
     */
    public function submitApplication(Request $request): JsonResponse
    {
        $user = $this->getAuthenticatedUser($request);
        if (!$user) {
            return $this->errorResponse('Tafadhali ingia kwanza kwenye akaunti yako.', 401);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:membership_categories,id',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'region_id' => 'required|integer',
            'district_id' => 'required|integer',
            'physical_address' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Tafadhali jaza sehemu zote za lazima.', 422, $validator->errors());
        }

        $category = MembershipCategory::find($request->input('category_id'));
        $isDriverCategory = ($category && $category->slug === 'full-member');

        if ($isDriverCategory && empty($request->input('driving_licence_number')) && empty($request->input('nida_number'))) {
            return $this->errorResponse('Kundi la Madereva linahitaji Nambari ya Leseni au NIDA.', 422);
        }

        $member = Member::where('user_id', $user->id)->first();
        if (!$member) {
            $member = new Member();
            $member->user_id = $user->id;
        }

        $year = date('Y');
        $memberCount = Member::whereYear('created_at', $year)->count() + 1;
        $membershipNumber = sprintf('TEVDA-%s-%06d', $year, $memberCount);

        $member->category_id = $request->input('category_id');
        $member->membership_number = $membershipNumber;
        $member->full_name = $request->input('full_name');
        $member->date_of_birth = $request->input('date_of_birth');
        $member->gender = strtolower($request->input('gender', 'male'));
        $member->phone = $request->input('phone');
        $member->email = $request->input('email') ?: $user->email;
        $member->physical_address = $request->input('physical_address');
        $member->region_id = $request->input('region_id');
        $member->district_id = $request->input('district_id');
        $member->ward_name = $request->input('ward_name');
        $member->nida_number = $request->input('nida_number');
        $member->driving_licence_number = $request->input('driving_licence_number');
        $member->licence_class = $request->input('licence_class');
        $member->occupation = $request->input('occupation', 'Electric Vehicle Operator');
        $member->ev_sector = $request->input('ev_sector', 'Clean Mobility Transport');
        $member->employer = $request->input('employer');
        $member->ev_experience_years = $request->input('ev_experience_years');
        $member->tin_number = $request->input('tin_number');
        $member->status = 'submitted'; // Submitted to secretariat review
        $member->save();

        // Register vehicle if driver category and plate provided
        if ($request->filled('vehicle_registration')) {
            Vehicle::updateOrCreate(
                [
                    'member_id' => $member->id,
                    'registration_number' => strtoupper(trim($request->input('vehicle_registration'))),
                ],
                [
                    'type' => $request->input('vehicle_type', 'two_wheeler'),
                    'make' => $request->input('vehicle_make', 'EV Maker'),
                    'model' => $request->input('vehicle_model', 'EV Model'),
                    'year' => $request->input('vehicle_year', date('Y')),
                    'ownership_type' => $request->input('ownership_type', 'owned'),
                    'battery_capacity_kwh' => $request->input('battery_capacity_kwh', 10.0),
                    'charging_type' => $request->input('charging_type', 'Battery Swap'),
                ]
            );
        }

        // Generate preliminary invoice with 12-digit GePG control number
        $controlNumber = '99' . str_pad((string)mt_rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT);
        $amount = $category ? (float)$category->registration_fee : 50000.0;
        if ($amount <= 0) $amount = 50000.0;

        Invoice::create([
            'member_id' => $member->id,
            'invoice_number' => 'INV-' . date('Ymd') . '-' . Str::random(5),
            'control_number' => $controlNumber,
            'amount' => $amount,
            'currency' => 'TZS',
            'status' => 'unpaid',
            'purpose' => 'Ada ya Usajili wa Uanachama TEVDA',
            'issued_date' => now(),
            'due_date' => now()->addDays(14),
        ]);

        return $this->successResponse([
            'application_number' => $membershipNumber,
            'member_number' => $membershipNumber,
            'status' => 'submitted',
            'control_number' => $controlNumber,
            'fee_amount' => $amount,
            'member' => $member->fresh(['category', 'region', 'district', 'vehicles', 'documents']),
        ], 'Hongera! Maombi yako yamewasilishwa rasmi kwa Sekretarieti ya TEVDA.', 201);
    }

    /**
     * Digital Smart ID details & QR payload
     * Note: NIDA and sensitive PII are strictly excluded from the public verification payload.
     */
    public function smartId(Request $request): JsonResponse
    {
        $member = $this->resolveMember($request);
        if (!$member) {
            $member = new Member([
                'membership_number' => 'TEVDA-2026-000128',
                'full_name' => 'Amani Juma',
                'status' => 'active',
            ]);
        }

        $activeVehicle = $member->vehicles ? $member->vehicles->first() : null;
        $primaryCard = $member->id ? MembershipCard::where('member_id', $member->id)->latest()->first() : null;

        $verificationUuid = $primaryCard ? $primaryCard->card_number : ($member->membership_number ?: ('TEVDA-VERIFY-' . strtoupper(Str::random(10))));
        $verificationUrl = "https://www.tevda.or.tz/verify-id/{$verificationUuid}";

        $smartIdData = [
            'member_number' => $member->membership_number,
            'full_name' => $member->full_name,
            'category_name' => $member->category ? $member->category->name : 'Full Member',
            'status' => $member->status,
            'is_active' => in_array($member->status, ['active', 'approved']),
            'photo_url' => $member->passport_photo_path ? asset('storage/' . $member->passport_photo_path) : null,
            'issue_date' => $member->approved_at ? $member->approved_at->format('Y-m-d') : date('Y-m-d'),
            'expiry_date' => $member->expiry_date ? $member->expiry_date->format('Y-m-d') : date('Y-m-d', strtotime('+1 year')),
            'vehicle_summary' => $activeVehicle ? ($activeVehicle->make . ' ' . $activeVehicle->model . ' (' . $activeVehicle->registration_number . ')') : 'Bila Chombo Kilichosajiliwa',
            'qr_verification_url' => $verificationUrl,
            'qr_verification_token' => $verificationUuid,
            'card_type' => 'Official Digital Smart ID',
            'watermark' => 'TANZANIA ELECTRIC VEHICLE DRIVERS ASSOCIATION',
            'security_notice' => 'Kitambulisho hiki kinalindwa kisheria na mfumo mkuu wa TEVDA. Data za NIDA zimehifadhiwa kwa usalama.',
        ];

        return $this->successResponse($smartIdData, 'Kitambulisho cha Kidijitali.');
    }

    /**
     * Certificate verification & metadata
     */
    public function certificate(Request $request): JsonResponse
    {
        $member = $this->resolveMember($request);
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $certificateData = [
            'certificate_number' => 'CERT-' . date('Y') . '-' . substr(str_replace('-', '', $member->membership_number ?: '000000'), -6),
            'member_name' => $member->full_name,
            'member_number' => $member->membership_number,
            'category' => $member->category ? $member->category->name : 'Full Member',
            'issuing_authority' => 'National Executive Council (TEVDA)',
            'signatory' => 'National Chairperson & General Secretary',
            'valid_until' => $member->expiry_date ? $member->expiry_date->format('d M Y') : date('d M Y', strtotime('+1 year')),
            'is_valid' => in_array($member->status, ['active', 'approved']),
            'download_url' => "https://www.tevda.or.tz/api/v1/member/certificate/pdf",
        ];

        return $this->successResponse($certificateData, 'Cheti rasmi cha uanachama.');
    }

    /**
     * Phase 5 & 7: Upload Membership Document (Multipart or Base64)
     */
    public function uploadDocument(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'document_type' => 'required|string',
            'file' => 'nullable|file|max:10240', // max 10MB
            'file_base64' => 'nullable|string',
            'file_name' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Faili halikidhi vigezo.', 422, $validator->errors());
        }

        $user = $this->getAuthenticatedUser($request);
        $member = $user ? Member::where('user_id', $user->id)->first() : Member::first();

        if (!$member && $user) {
            $member = Member::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'phone' => $user->phone,
                'email' => $user->email,
                'status' => 'draft',
            ]);
        }

        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $path = '';
        $fileName = 'document.pdf';
        $fileSize = 0;
        $mimeType = 'application/pdf';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('membership_documents', 'public');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $mimeType = $file->getMimeType();
        } elseif ($request->filled('file_base64')) {
            $base64 = $request->input('file_base64');
            $fileName = $request->input('file_name', 'upload_' . time() . '.png');
            $binary = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64));
            $path = 'membership_documents/' . Str::random(20) . '_' . $fileName;
            Storage::disk('public')->put($path, $binary);
            $fileSize = strlen($binary);
            $mimeType = 'image/png';
        } else {
            return $this->errorResponse('Tafadhali chagua faili la kupakia.', 422);
        }

        // If it's passport photo, also set on member record
        if ($request->input('document_type') === 'passport_photo') {
            $member->passport_photo_path = $path;
            $member->save();
        }

        $doc = MembershipDocument::create([
            'member_id' => $member->id,
            'document_type' => $request->input('document_type'),
            'file_path' => $path,
            'file_name' => $fileName,
            'file_size' => $fileSize,
            'mime_type' => $mimeType,
            'verification_status' => 'pending',
        ]);

        return $this->successResponse([
            'id' => $doc->id,
            'document_type' => $doc->document_type,
            'file_name' => $doc->file_name,
            'file_url' => asset('storage/' . $path),
            'verification_status' => $doc->verification_status,
        ], 'Nyaraka imepakiwa kwa mafanikio.', 201);
    }

    /**
     * Get list of uploaded documents
     */
    public function getDocuments(Request $request): JsonResponse
    {
        $member = $this->resolveMember($request);
        if (!$member) {
            return $this->successResponse([], 'Hakuna nyaraka.');
        }

        $docs = MembershipDocument::where('member_id', $member->id)->latest()->get()->map(function ($d) {
            return [
                'id' => $d->id,
                'document_type' => $d->document_type,
                'file_name' => $d->file_name,
                'file_url' => asset('storage/' . $d->file_path),
                'verification_status' => $d->verification_status,
                'created_at' => $d->created_at->format('d M Y'),
            ];
        });

        return $this->successResponse($docs, 'Nyaraka za mwanachama.');
    }

    /**
     * Delete uploaded draft document
     */
    public function deleteDocument(Request $request, int $id): JsonResponse
    {
        $user = $this->getAuthenticatedUser($request);
        $member = $user ? Member::where('user_id', $user->id)->first() : Member::first();
        if (!$member) {
            return $this->errorResponse('Huna ruhusa ya kufuta nyaraka hii.', 403);
        }

        $doc = MembershipDocument::where('id', $id)->where('member_id', $member->id)->first();
        if (!$doc) {
            return $this->errorResponse('Nyaraka haijapatikana.', 404);
        }

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();

        return $this->successResponse(null, 'Nyaraka imefutwa kikamilifu.');
    }
}

