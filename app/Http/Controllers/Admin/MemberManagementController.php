<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\User;
use App\Models\Role;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\District;
use App\Models\MembershipCategory;
use App\Models\MembershipDocument;
use App\Models\MembershipCard;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Region;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\PdfService;
use App\Services\QrCodeService;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MemberManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with(['category', 'region', 'primaryVehicle', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('membership_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        $members = $query->latest()->paginate(15);
        $categories = MembershipCategory::all();
        $regions = Region::all();

        return view('admin.members.index', compact('members', 'categories', 'regions'));
    }

    public function create()
    {
        $categories = MembershipCategory::where('is_active', true)->orderBy('order_number')->get();
        $regions = Region::with('districts')->where('is_active', true)->orderBy('name')->get();

        return view('admin.members.create', compact('categories', 'regions'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'nullable|string|min:6',
            'category_id' => 'required|exists:membership_categories,id',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'physical_address' => 'required|string|max:255',
            'nida_number' => 'nullable|string|max:30',
            'driving_licence_number' => 'nullable|string|max:50',
            'licence_class' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:100',
            'ev_sector' => 'nullable|string|max:100',
            'is_founding_member' => 'nullable|boolean',
            'initial_status' => 'required|in:submitted,payment_pending,payment_confirmed,approved',
            
            // Vehicle
            'vehicle_type' => 'nullable|string|in:two_wheeler,three_wheeler,passenger_car,van,minibus,bus,commercial_truck,other',
            'vehicle_make' => 'nullable|string|max:50',
            'vehicle_model' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:30',
            'ownership_type' => 'nullable|string|in:owned,leased,company_owned,driver_operated,other',
            'charging_type' => 'nullable|string|in:ac_slow,dc_fast,battery_swap,dual,other',
            
            // Files
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'nida_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'driving_licence_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ];

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            // 1. Create User Account
            $memberRole = Role::where('slug', 'member')->first();
            $rawPassword = $request->filled('password') ? $request->password : 'Tevda@' . date('Y');
            
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($rawPassword),
                'role_id' => $memberRole?->id,
                'is_active' => true,
            ]);

            // 2. Create Member Record
            $member = Member::create([
                'user_id' => $user->id,
                'category_id' => $validated['category_id'],
                'region_id' => $validated['region_id'],
                'district_id' => $validated['district_id'] ?? null,
                'full_name' => $validated['name'],
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'],
                'phone' => $validated['phone'],
                'email' => $validated['email'],
                'physical_address' => $validated['physical_address'],
                'nida_number' => $validated['nida_number'] ?? null,
                'driving_licence_number' => $validated['driving_licence_number'] ?? null,
                'licence_class' => $validated['licence_class'] ?? null,
                'occupation' => $validated['occupation'] ?? null,
                'ev_sector' => $validated['ev_sector'] ?? null,
                'is_founding_member' => $request->boolean('is_founding_member', false),
                'status' => $validated['initial_status'],
                'created_by' => Auth::id(),
            ]);

            // 3. Handle Passport Photo
            if ($request->hasFile('passport_photo')) {
                $photoPath = DocumentService::storePublic($request->file('passport_photo'), 'avatars');
                $member->update(['passport_photo_path' => $photoPath]);
                $user->update(['avatar_path' => $photoPath]);
            }

            // 4. Handle NIDA Document
            if ($request->hasFile('nida_document')) {
                $doc = DocumentService::storePrivate($request->file('nida_document'), 'nida');
                MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'nida',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => $validated['initial_status'] === 'approved' ? 'verified' : 'pending',
                    'verified_by' => $validated['initial_status'] === 'approved' ? Auth::id() : null,
                    'verified_at' => $validated['initial_status'] === 'approved' ? now() : null,
                ]);
            }

            // 5. Handle Driving Licence Document
            if ($request->hasFile('driving_licence_document')) {
                $doc = DocumentService::storePrivate($request->file('driving_licence_document'), 'driving_licence');
                MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'driving_licence',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => $validated['initial_status'] === 'approved' ? 'verified' : 'pending',
                    'verified_by' => $validated['initial_status'] === 'approved' ? Auth::id() : null,
                    'verified_at' => $validated['initial_status'] === 'approved' ? now() : null,
                ]);
            }

            // 6. Handle Vehicle
            if (!empty($validated['vehicle_type']) || !empty($validated['vehicle_registration'])) {
                Vehicle::create([
                    'member_id' => $member->id,
                    'vehicle_type' => $validated['vehicle_type'] ?? 'three_wheeler',
                    'make' => $validated['vehicle_make'] ?? null,
                    'model' => $validated['vehicle_model'] ?? null,
                    'registration_number' => $validated['vehicle_registration'] ?? null,
                    'ownership_type' => $validated['ownership_type'] ?? 'driver_operated',
                    'charging_type' => $validated['charging_type'] ?? 'battery_swap',
                ]);
            }

            $category = MembershipCategory::findOrFail($validated['category_id']);

            // 7. Handle Invoice if fee configured or payment pending
            if ($category->registration_fee > 0 || $validated['initial_status'] === 'payment_pending') {
                $feeAmount = $category->registration_fee > 0 ? $category->registration_fee : 50000;
                Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'member_id' => $member->id,
                    'user_id' => $user->id,
                    'amount' => $feeAmount,
                    'currency' => 'TZS',
                    'purpose' => 'Membership Registration Fee (' . $category->name . ')',
                    'status' => in_array($validated['initial_status'], ['approved', 'payment_confirmed']) ? 'paid' : 'unpaid',
                    'due_date' => now()->addDays(14),
                ]);
            }

            // 8. If initial_status is APPROVED -> Generate Member Number, Digital ID Card & Certificate
            if ($validated['initial_status'] === 'approved') {
                $member->membership_number = Member::generateMembershipNumber();
                $member->approved_by = Auth::id();
                $member->approved_at = now();
                $member->expiry_date = now()->addYear();
                $member->reviewer_notes = 'Registered and directly approved by Administrator ' . Auth::user()->name;
                $member->save();

                // Digital Membership Card
                $verifyMembershipUrl = url('/verify/membership/' . $member->membership_number);
                MembershipCard::updateOrCreate(
                    ['member_id' => $member->id],
                    [
                        'card_number' => 'CARD-' . $member->membership_number,
                        'issue_date' => now(),
                        'expiry_date' => $member->expiry_date,
                        'qr_code_path' => $verifyMembershipUrl,
                        'card_data' => [
                            'name' => $member->full_name,
                            'category' => $category->name,
                            'region' => $member->region?->name ?? 'Tanzania',
                            'phone' => $member->phone,
                            'motto' => 'SMART DRIVERS SMART MOBILITY',
                        ],
                        'is_active' => true,
                    ]
                );

                // Official Certificate of Membership
                $membershipTemplate = CertificateTemplate::where('certificate_type', 'membership')->first();
                $certNumber = Certificate::generateCertificateNumber('MEM');
                $verifyCertUrl = url('/verify/certificate/' . $certNumber);

                Certificate::updateOrCreate(
                    ['member_id' => $member->id, 'certificate_type' => 'membership'],
                    [
                        'certificate_number' => $certNumber,
                        'template_id' => $membershipTemplate?->id,
                        'title' => 'Certificate of Membership',
                        'recipient_name' => $member->full_name,
                        'course_name' => $category->name,
                        'issue_date' => now(),
                        'expiry_date' => $member->expiry_date,
                        'authorized_person_name' => 'Dr. Charles Mwansasu',
                        'authorized_person_title' => 'Founding Chairperson',
                        'qr_code_path' => $verifyCertUrl,
                        'status' => 'valid',
                    ]
                );
            }

            AuditLog::log('created_member', 'membership', (string)$member->id, null, [
                'full_name' => $member->full_name,
                'status' => $member->status,
                'membership_number' => $member->membership_number ?? 'Pending',
            ]);

            NotificationCustom::send(
                $user->id,
                'Welcome to TEVDA Registry',
                "Your TEVDA membership profile has been created. Status: " . ucfirst(str_replace('_', ' ', $member->status)),
                route('portal.dashboard'),
                'success'
            );

            DB::commit();

            return redirect()->route('admin.members.show', $member->id)
                ->with('success', "Member {$member->full_name} registered successfully! " . ($member->status === 'approved' ? "Membership Number: {$member->membership_number}, ID Card & Certificate generated." : ""));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Failed to register member: ' . $e->getMessage()]);
        }
    }

    public function show(int $id)
    {
        $member = Member::with([
            'user',
            'category',
            'region',
            'district',
            'ward',
            'branch',
            'documents.verifier',
            'card',
            'vehicles',
            'certificates',
            'invoices.payments',
            'enrolments.session.course',
            'opportunityApplications.opportunity',
            'projectApplications.project'
        ])->findOrFail($id);

        return view('admin.members.show', compact('member'));
    }

    public function verifyDocument(Request $request, int $documentId)
    {
        $request->validate([
            'status' => 'required|in:verified,rejected',
            'notes' => 'nullable|string|max:500',
        ]);

        $document = MembershipDocument::findOrFail($documentId);
        $oldStatus = $document->verification_status;

        $document->update([
            'verification_status' => $request->status,
            'notes' => $request->notes,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        AuditLog::log('verified_document', 'membership', (string)$document->id, ['status' => $oldStatus], ['status' => $request->status]);

        return back()->with('success', "Document status updated to {$request->status}.");
    }

    public function approve(Request $request, int $id)
    {
        $member = Member::with(['category', 'user'])->findOrFail($id);

        if ($member->status === 'approved') {
            return back()->with('info', 'This member is already approved.');
        }

        DB::beginTransaction();
        try {
            // 1. Generate Membership Number if not present
            if (!$member->membership_number) {
                $member->membership_number = Member::generateMembershipNumber();
            }

            $member->status = 'approved';
            $member->approved_by = Auth::id();
            $member->approved_at = now();
            $member->expiry_date = now()->addYear(); // 1 year validity
            $member->reviewer_notes = $request->input('notes', 'Application approved by TEVDA Administration.');
            $member->save();

            // 2. Generate Digital Membership Card
            $verifyMembershipUrl = url('/verify/membership/' . $member->membership_number);
            MembershipCard::updateOrCreate(
                ['member_id' => $member->id],
                [
                    'card_number' => 'CARD-' . $member->membership_number,
                    'issue_date' => now(),
                    'expiry_date' => $member->expiry_date,
                    'qr_code_path' => $verifyMembershipUrl,
                    'card_data' => [
                        'name' => $member->full_name,
                        'category' => $member->category->name,
                        'region' => $member->region?->name ?? 'Tanzania',
                        'phone' => $member->phone,
                        'motto' => 'SMART DRIVERS SMART MOBILITY',
                    ],
                    'is_active' => true,
                ]
            );

            // 3. Generate Official Membership Certificate
            $membershipTemplate = CertificateTemplate::where('certificate_type', 'membership')->first();
            $certNumber = Certificate::generateCertificateNumber('MEM');
            $verifyCertUrl = url('/verify/certificate/' . $certNumber);

            Certificate::updateOrCreate(
                ['member_id' => $member->id, 'certificate_type' => 'membership'],
                [
                    'certificate_number' => $certNumber,
                    'template_id' => $membershipTemplate?->id,
                    'title' => 'Certificate of Membership',
                    'recipient_name' => $member->full_name,
                    'course_name' => $member->category->name,
                    'issue_date' => now(),
                    'expiry_date' => $member->expiry_date,
                    'authorized_person_name' => 'Dr. Charles Mwansasu',
                    'authorized_person_title' => 'Founding Chairperson',
                    'qr_code_path' => $verifyCertUrl,
                    'status' => 'valid',
                ]
            );

            AuditLog::log('approved_member', 'membership', (string)$member->id, ['status' => 'pending'], ['status' => 'approved', 'membership_number' => $member->membership_number]);

            NotificationCustom::send(
                $member->user_id,
                'Congratulations! Membership Approved',
                "Your TEVDA membership has been approved. Membership Number: {$member->membership_number}. Your Digital ID Card and Certificate of Membership are now active in your dashboard.",
                route('portal.dashboard'),
                'success'
            );

            DB::commit();

            return back()->with('success', "Member {$member->full_name} has been APPROVED! Membership number: {$member->membership_number}. Digital ID card and Membership certificate generated.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Approval failed: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, int $id)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $member = Member::findOrFail($id);
        $oldStatus = $member->status;

        $member->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        AuditLog::log('rejected_member', 'membership', (string)$member->id, ['status' => $oldStatus], ['status' => 'rejected', 'reason' => $request->reason]);

        NotificationCustom::send(
            $member->user_id,
            'Membership Application Update',
            "Your membership application was not approved. Reason: {$request->reason}. Please contact TEVDA for guidance.",
            route('portal.dashboard'),
            'danger'
        );

        return back()->with('success', "Member application rejected.");
    }

    public function markIncomplete(Request $request, int $id)
    {
        $request->validate([
            'notes' => 'required|string|max:1000',
        ]);

        $member = Member::findOrFail($id);
        $member->update([
            'status' => 'documents_incomplete',
            'reviewer_notes' => $request->notes,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        AuditLog::log('requested_documents', 'membership', (string)$member->id, null, ['notes' => $request->notes]);

        NotificationCustom::send(
            $member->user_id,
            'Additional Documents Required',
            "Your TEVDA membership review requires additional action: {$request->notes}. Please update your profile or re-upload documents.",
            route('portal.profile'),
            'warning'
        );

        return back()->with('success', 'Member notified of incomplete documents.');
    }
}
