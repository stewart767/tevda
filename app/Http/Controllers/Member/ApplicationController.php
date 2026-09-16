<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\MembershipDocument;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Region;
use App\Models\District;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApplicationController extends Controller
{
    public function showForm(Request $request)
    {
        $user = Auth::user();
        $member = $user->member;

        // If member already has an approved membership, redirect to dashboard
        if ($member && $member->status === 'approved') {
            return redirect()->route('portal.dashboard');
        }

        $categories = MembershipCategory::where('is_active', true)->orderBy('order_number')->get();
        $regions = Region::with('districts')->where('is_active', true)->orderBy('name')->get();
        $selectedCategorySlug = $request->query('category', $member?->category?->slug ?? 'full-member');
        $selectedCategory = MembershipCategory::where('slug', $selectedCategorySlug)->first() ?? $categories->first();

        return view('portal.application_form', compact('categories', 'regions', 'selectedCategory', 'member'));
    }

    public function submitApplication(Request $request)
    {
        $user = Auth::user();
        
        $rules = [
            'category_id' => 'required|exists:membership_categories,id',
            'full_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'phone' => 'required|string|max:20',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'physical_address' => 'required|string|max:255',
            'nida_number' => 'nullable|string|max:30',
            
            // Professional & Vehicle
            'occupation' => 'nullable|string|max:100',
            'ev_sector' => 'nullable|string|max:100',
            'vehicle_type' => 'nullable|string|in:two_wheeler,three_wheeler,passenger_car,van,minibus,bus,commercial_truck,other',
            'vehicle_make' => 'nullable|string|max:50',
            'vehicle_model' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:30',
            'ownership_type' => 'nullable|string|in:owned,leased,company_owned,driver_operated,other',
            'charging_type' => 'nullable|string|in:ac_slow,dc_fast,battery_swap,dual,other',

            // Files
            'nida_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'driving_licence_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
        ];

        // Specific category checks
        $category = MembershipCategory::findOrFail($request->category_id);
        if ($category->slug === 'full-member') {
            $rules['driving_licence_number'] = 'required|string|max:50';
            $rules['licence_class'] = 'required|string|max:20';
        }

        $validated = $request->validate($rules);

        DB::beginTransaction();
        try {
            $member = Member::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'category_id' => $validated['category_id'],
                    'region_id' => $validated['region_id'],
                    'district_id' => $validated['district_id'] ?? null,
                    'full_name' => $validated['full_name'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'gender' => $validated['gender'],
                    'phone' => $validated['phone'],
                    'email' => $user->email,
                    'physical_address' => $validated['physical_address'],
                    'nida_number' => $validated['nida_number'] ?? null,
                    'driving_licence_number' => $validated['driving_licence_number'] ?? null,
                    'licence_class' => $validated['licence_class'] ?? null,
                    'occupation' => $validated['occupation'] ?? null,
                    'ev_sector' => $validated['ev_sector'] ?? null,
                    'status' => 'submitted',
                ]
            );

            // Handle Passport Photo
            if ($request->hasFile('passport_photo')) {
                $photoPath = DocumentService::storePublic($request->file('passport_photo'), 'avatars');
                $member->update(['passport_photo_path' => $photoPath]);
                $user->update(['avatar_path' => $photoPath]);
            }

            // Handle NIDA Document
            if ($request->hasFile('nida_document')) {
                $doc = DocumentService::storePrivate($request->file('nida_document'), 'nida');
                MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'nida',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => 'pending',
                ]);
            }

            // Handle Driving Licence Document
            if ($request->hasFile('driving_licence_document')) {
                $doc = DocumentService::storePrivate($request->file('driving_licence_document'), 'driving_licence');
                MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'driving_licence',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => 'pending',
                ]);
            }

            // Handle Vehicle Record if provided
            if (!empty($validated['vehicle_type']) || !empty($validated['vehicle_registration'])) {
                Vehicle::updateOrCreate(
                    ['member_id' => $member->id],
                    [
                        'vehicle_type' => $validated['vehicle_type'] ?? 'three_wheeler',
                        'make' => $validated['vehicle_make'] ?? null,
                        'model' => $validated['vehicle_model'] ?? null,
                        'registration_number' => $validated['vehicle_registration'] ?? null,
                        'ownership_type' => $validated['ownership_type'] ?? 'driver_operated',
                        'charging_type' => $validated['charging_type'] ?? 'battery_swap',
                    ]
                );
            }

            // Generate Registration Invoice if fee configured > 0
            if ($category->registration_fee > 0) {
                $invoice = Invoice::create([
                    'invoice_number' => Invoice::generateInvoiceNumber(),
                    'member_id' => $member->id,
                    'user_id' => $user->id,
                    'amount' => $category->registration_fee,
                    'currency' => 'TZS',
                    'purpose' => 'Membership Registration Fee (' . $category->name . ')',
                    'status' => 'unpaid',
                    'due_date' => now()->addDays(14),
                ]);
                $member->update(['status' => 'payment_pending']);
            }

            AuditLog::log('submitted_application', 'membership', (string)$member->id, null, [
                'category' => $category->name,
                'full_name' => $member->full_name,
            ]);

            NotificationCustom::send(
                $user->id,
                'Membership Application Received',
                'Your membership application for ' . $category->name . ' has been submitted successfully and is now undergoing TEVDA Secretariat verification.',
                route('portal.dashboard'),
                'success'
            );

            DB::commit();

            return redirect()->route('portal.dashboard')->with('success', 'Your membership application has been submitted successfully! You can track your verification status below.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'An error occurred while saving your application: ' . $e->getMessage()]);
        }
    }
}
