<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Support login by either email or phone
        $field = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$field => $credentials['email'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is deactivated. Please contact TEVDA administration.']);
            }

            $user->update(['last_login_at' => now()]);
            AuditLog::log('login', 'auth', (string)$user->id, null, ['ip' => $request->ip()]);

            return $this->authenticatedRedirect($user);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(Request $request)
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }

        $categories = \App\Models\MembershipCategory::where('is_active', true)->orderBy('order_number')->get();
        $regions = \App\Models\Region::with('districts')->where('is_active', true)->orderBy('name')->get();
        $selectedCategorySlug = $request->query('category', 'full-member');
        $selectedCategory = \App\Models\MembershipCategory::where('slug', $selectedCategorySlug)->first() ?? $categories->first();

        return view('auth.register', compact('categories', 'regions', 'selectedCategory'));
    }

    public function register(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::min(6)],
            'terms' => 'accepted',

            // Membership Application fields
            'category_id' => 'required|exists:membership_categories,id',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'required|in:male,female,other',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'physical_address' => 'required|string|max:255',
            'nida_number' => 'nullable|string|max:30',
            'driving_licence_number' => 'nullable|string|max:50',
            'licence_class' => 'nullable|string|max:20',
            
            // Vehicle Details
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

        $category = \App\Models\MembershipCategory::findOrFail($request->category_id);
        if ($category->slug === 'full-member') {
            $rules['driving_licence_number'] = 'required|string|max:50';
        }

        $validated = $request->validate($rules);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            // 1. Create User
            $memberRole = Role::where('slug', 'member')->first();
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($request->password),
                'role_id' => $memberRole?->id,
                'is_active' => true,
            ]);

            // 2. Create Member Profile
            $member = \App\Models\Member::create([
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
                'status' => 'payment_pending',
            ]);

            // 3. Upload Passport Photo
            if ($request->hasFile('passport_photo')) {
                $photoPath = \App\Services\DocumentService::storePublic($request->file('passport_photo'), 'avatars');
                $member->update(['passport_photo_path' => $photoPath]);
                $user->update(['avatar_path' => $photoPath]);
            }

            // 4. Upload Documents
            if ($request->hasFile('nida_document')) {
                $doc = \App\Services\DocumentService::storePrivate($request->file('nida_document'), 'nida');
                \App\Models\MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'nida',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => 'pending',
                ]);
            }

            if ($request->hasFile('driving_licence_document')) {
                $doc = \App\Services\DocumentService::storePrivate($request->file('driving_licence_document'), 'driving_licence');
                \App\Models\MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'driving_licence',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => 'pending',
                ]);
            }

            // 5. Create Vehicle
            if (!empty($validated['vehicle_type']) || !empty($validated['vehicle_registration'])) {
                \App\Models\Vehicle::create([
                    'member_id' => $member->id,
                    'vehicle_type' => $validated['vehicle_type'] ?? 'three_wheeler',
                    'make' => $validated['vehicle_make'] ?? null,
                    'model' => $validated['vehicle_model'] ?? null,
                    'registration_number' => $validated['vehicle_registration'] ?? null,
                    'ownership_type' => $validated['ownership_type'] ?? 'driver_operated',
                    'charging_type' => $validated['charging_type'] ?? 'battery_swap',
                ]);
            }

            // 6. Generate Invoice with 12-digit Control Number
            $feeAmount = $category->registration_fee > 0 ? $category->registration_fee : 50000;
            $invoice = \App\Models\Invoice::create([
                'invoice_number' => \App\Models\Invoice::generateInvoiceNumber(),
                'control_number' => \App\Models\Invoice::generateControlNumber(),
                'member_id' => $member->id,
                'user_id' => $user->id,
                'amount' => $feeAmount,
                'currency' => 'TZS',
                'purpose' => 'Membership Registration Fee (' . $category->name . ')',
                'status' => 'unpaid',
                'due_date' => now()->addDays(14),
            ]);

            Auth::login($user);

            AuditLog::log('register', 'auth', (string)$user->id, null, [
                'email' => $user->email,
                'control_number' => $invoice->control_number,
                'full_name' => $member->full_name,
            ]);

            \App\Models\NotificationCustom::send(
                $user->id,
                'Registration Successful!',
                "Welcome to TEVDA! Your Payment Control Number is: {$invoice->control_number}. Please complete payment of {$invoice->amount} TZS via M-Pesa, Tigo Pesa, or Bank to unlock your Certificate.",
                route('portal.dashboard'),
                'success'
            );

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('portal.dashboard')->with('success', "Registration successful! Your Payment Control Number is: {$invoice->control_number}. Please pay {$invoice->amount} TZS using M-Pesa, Tigo Pesa, Airtel Money, or Bank to complete your application and unlock your Certificate.");
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Registration error: ' . $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        $userId = Auth::id();
        AuditLog::log('logout', 'auth', (string)$userId);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'You have been logged out successfully.');
    }

    protected function authenticatedRedirect(User $user)
    {
        if ($user->isStaff()) {
            return redirect()->intended(route('admin.dashboard'));
        }
        return redirect()->intended(route('portal.dashboard'));
    }
}
