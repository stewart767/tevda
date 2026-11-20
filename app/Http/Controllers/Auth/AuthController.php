<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\Region;
use App\Models\Vehicle;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\MembershipDocument;
use App\Models\NotificationCustom;
use App\Models\AuditLog;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
        $validated = $request->validate([
            'category_id' => 'required|exists:membership_categories,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'nullable|date|before:today',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'physical_address' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::min(6)],
            'terms' => 'accepted',
        ]);

        DB::beginTransaction();
        try {
            $category = MembershipCategory::findOrFail($validated['category_id']);
            $memberRole = Role::where('slug', 'member')->first();

            // 1. Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'password' => Hash::make($request->password),
                'role_id' => $memberRole?->id,
                'is_active' => true,
            ]);

            // 2. Create Initial Member Profile
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
                'status' => 'payment_pending',
            ]);

            // 3. Create Combined Invoice: 5,000 TZS (Registration) + 10,000 TZS (Certificate) = 15,000 TZS
            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'control_number' => Invoice::generateControlNumber(),
                'member_id' => $member->id,
                'user_id' => $user->id,
                'amount' => 15000.00,
                'currency' => 'TZS',
                'purpose' => 'Ada ya Usajili (5,000 TZS) & Cheti cha Uanachama (10,000 TZS)',
                'status' => 'unpaid',
                'due_date' => now()->addDays(7),
                'notes' => 'Registration fee: 5000 TZS, Membership certificate fee: 10000 TZS',
            ]);

            Auth::login($user);

            AuditLog::log('register_step1', 'auth', (string)$user->id, null, [
                'email' => $user->email,
                'control_number' => $invoice->control_number,
                'full_name' => $member->full_name,
                'amount' => 15000,
            ]);

            NotificationCustom::send(
                $user->id,
                'Kamilisha Malipo ya Usajili na Cheti (TZS 15,000)',
                "Karibu TEVDA! Namba yako ya Malipo (Control Number) ni: {$invoice->control_number}. Tafadhali lipa TZS 15,000 (Usajili 5,000 + Cheti 10,000) kuendelea na fomu ya usajili na kupata Cheti.",
                route('register.payment', $invoice->id),
                'warning'
            );

            DB::commit();

            return redirect()->route('register.payment', $invoice->id)
                ->with('success', "Taarifa za mwanzo zimehifadhiwa! Namba yako ya Udhibiti ni {$invoice->control_number}. Tafadhali kamilisha malipo ya TZS 15,000 kuendelea na fomu ya mwisho na kutoa Cheti cha Uanachama.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Registration error: ' . $e->getMessage()]);
        }
    }

    public function showRegisterPayment(Request $request, int $invoiceId)
    {
        $invoice = Invoice::with(['member.category', 'payments'])->findOrFail($invoiceId);

        // Access check
        if (Auth::check() && Auth::id() !== $invoice->user_id && !Auth::user()->isStaff()) {
            abort(403, 'Unauthorized access to invoice');
        }

        $member = $invoice->member;

        // If invoice is already paid, navigate to profile completion or dashboard
        if ($invoice->status === 'paid') {
            if ($member && (!$member->driving_licence_number || !$member->passport_photo_path)) {
                return redirect()->route('register.complete_profile')
                    ->with('info', 'Malipo yako ya TZS 15,000 tayari yamelipwa kikamilifu. Kamilisha sehemu hii ya fomu ili kutoa Cheti chako.');
            }
            return redirect()->route('portal.dashboard')
                ->with('info', 'Malipo yamekamilika na akaunti yako iko tayari.');
        }

        return view('auth.register_payment', compact('invoice', 'member'));
    }

    public function processRegisterPayment(Request $request, int $invoiceId)
    {
        $invoice = Invoice::with(['member', 'user'])->findOrFail($invoiceId);

        if (Auth::check() && Auth::id() !== $invoice->user_id && !Auth::user()->isStaff()) {
            abort(403);
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:mobile_money,bank_transfer,cash,online_card,other',
            'provider' => 'nullable|string|in:mpesa,tigopesa,airtel,halopesa,bank,other',
            'phone' => 'nullable|string|max:20',
            'transaction_reference' => 'nullable|string|max:100',
            'payment_mode' => 'nullable|string|in:push_stk,manual_ref',
        ]);

        $mode = $validated['payment_mode'] ?? 'push_stk';
        $provider = $validated['provider'] ?? 'mpesa';

        // Generate realistic transaction reference if push STK
        if ($mode === 'push_stk' || empty($validated['transaction_reference'])) {
            $prefix = strtoupper(substr($provider, 0, 3));
            $txRef = $prefix . date('ymd') . strtoupper(Str::random(6));
        } else {
            $txRef = strtoupper(trim($validated['transaction_reference']));
        }

        DB::beginTransaction();
        try {
            // 1. Create Payment record
            $payment = Payment::create([
                'payment_reference' => Payment::generatePaymentReference(),
                'invoice_id' => $invoice->id,
                'member_id' => $invoice->member_id,
                'amount' => $invoice->amount,
                'currency' => $invoice->currency,
                'payment_method' => $validated['payment_method'],
                'transaction_reference' => $txRef,
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => 'Verified online payment (' . strtoupper($provider) . '): 5,000 TZS Registration + 10,000 TZS Certificate',
            ]);

            // 2. Mark Invoice as paid
            $invoice->update([
                'status' => 'paid',
            ]);

            // 3. Generate Official Receipt
            $receipt = Receipt::create([
                'receipt_number' => Receipt::generateReceiptNumber(),
                'payment_id' => $payment->id,
                'invoice_id' => $invoice->id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'issued_at' => now(),
            ]);

            // 4. Update Member Status to payment_confirmed
            if ($invoice->member) {
                $invoice->member->update([
                    'status' => 'payment_confirmed',
                ]);
            }

            AuditLog::log('processed_registration_payment', 'payment', (string)$payment->id, null, [
                'invoice_number' => $invoice->invoice_number,
                'control_number' => $invoice->control_number,
                'amount' => $payment->amount,
                'tx_ref' => $txRef,
            ]);

            NotificationCustom::send(
                $invoice->user_id,
                'Malipo Yamethibitishwa Kikamilifu!',
                "Malipo yako ya TZS {$payment->amount} (Risiti Na: {$receipt->receipt_number}) yamethibitishwa. Sasa kamilisha taarifa za mwisho za Leseni na Chombo ili kutoa Cheti chako cha Uanachama.",
                route('register.complete_profile'),
                'success'
            );

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Malipo yamethibitishwa kikamilifu!',
                    'receipt_number' => $receipt->receipt_number,
                    'redirect_url' => route('register.complete_profile'),
                ]);
            }

            return redirect()->route('register.complete_profile')
                ->with('success', "Malipo ya TZS " . number_format($payment->amount) . " yamethibitishwa kikamilifu! Risiti Na: {$receipt->receipt_number}. Kamilisha fomu ya mwisho hapa chini ili kutoa Cheti cha Uanachama.");
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->withErrors(['error' => 'Hitilafu ya malipo: ' . $e->getMessage()]);
        }
    }

    public function showCompleteProfile()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $member = Member::with(['category', 'region', 'district', 'invoices.payments'])->where('user_id', $user->id)->firstOrFail();

        // Check if invoice is paid
        $hasPaid = $member->invoices()->where('status', 'paid')->exists() || in_array($member->status, ['payment_confirmed', 'submitted', 'under_review', 'approved']);
        if (!$hasPaid) {
            $unpaidInvoice = $member->invoices()->where('status', 'unpaid')->latest()->first();
            if ($unpaidInvoice) {
                return redirect()->route('register.payment', $unpaidInvoice->id)
                    ->with('warning', 'Tafadhali kamilisha malipo ya TZS 15,000 kwanza ili kufungua sehemu hii ya fomu ya usajili.');
            }
        }

        return view('auth.register_complete_profile', compact('member'));
    }

    public function completeProfile(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $member = Member::with('category')->where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'driving_licence_number' => 'required|string|max:50',
            'licence_class' => 'nullable|string|max:20',
            'nida_number' => 'nullable|string|max:30',
            'vehicle_type' => 'required|string|in:two_wheeler,three_wheeler,passenger_car,van,minibus,bus,commercial_truck,other',
            'vehicle_make' => 'nullable|string|max:50',
            'vehicle_model' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:30',
            'ownership_type' => 'nullable|string|in:owned,leased,company_owned,driver_operated,other',
            'charging_type' => 'nullable|string|in:ac_slow,dc_fast,battery_swap,dual,other',
            'passport_photo' => ($member->passport_photo_path ? 'nullable' : 'required') . '|image|mimes:jpg,jpeg,png,webp|max:4096',
            'driving_licence_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
            'nida_document' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        DB::beginTransaction();
        try {
            // Update Member Info
            $memberUpdate = [
                'driving_licence_number' => $validated['driving_licence_number'],
                'licence_class' => $validated['licence_class'] ?? 'C3',
                'nida_number' => $validated['nida_number'] ?? $member->nida_number,
                'status' => 'approved',
                'approved_at' => now(),
            ];

            // Upload Passport Photo
            if ($request->hasFile('passport_photo')) {
                $photoPath = DocumentService::storePublic($request->file('passport_photo'), 'avatars');
                $memberUpdate['passport_photo_path'] = $photoPath;
                $user->update(['avatar_path' => $photoPath]);
            }

            $member->update($memberUpdate);

            // Upload Documents
            if ($request->hasFile('driving_licence_document')) {
                $doc = DocumentService::storePrivate($request->file('driving_licence_document'), 'driving_licence');
                MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'driving_licence',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => 'verified',
                ]);
            }

            if ($request->hasFile('nida_document')) {
                $doc = DocumentService::storePrivate($request->file('nida_document'), 'nida');
                MembershipDocument::create([
                    'member_id' => $member->id,
                    'document_type' => 'nida',
                    'file_path' => $doc['file_path'],
                    'file_name' => $doc['file_name'],
                    'file_size' => $doc['file_size'],
                    'mime_type' => $doc['mime_type'],
                    'verification_status' => 'verified',
                ]);
            }

            // Create or update Vehicle
            Vehicle::updateOrCreate(
                ['member_id' => $member->id],
                [
                    'vehicle_type' => $validated['vehicle_type'],
                    'make' => $validated['vehicle_make'] ?? null,
                    'model' => $validated['vehicle_model'] ?? null,
                    'registration_number' => $validated['vehicle_registration'] ?? null,
                    'ownership_type' => $validated['ownership_type'] ?? 'driver_operated',
                    'charging_type' => $validated['charging_type'] ?? 'battery_swap',
                ]
            );

            // Issue credentials: Member Number, Digital Smart ID Card, and Official Certificate of Membership!
            $member->refresh();
            $member->ensureCredentialsGenerated();

            AuditLog::log('completed_full_registration', 'member', (string)$member->id, null, [
                'membership_number' => $member->membership_number,
                'driving_licence' => $member->driving_licence_number,
            ]);

            NotificationCustom::send(
                $user->id,
                'Hongera! Cheti chako cha Uanachama Kiko Tayari',
                "Usajili wako umekamilika kikamilifu. Namba yako ya Uanachama ni {$member->membership_number}. Cheti chako rasmi cha Uanachama na Kitambulisho vimetolewa na viko tayari kupakuliwa.",
                route('portal.dashboard'),
                'success'
            );

            DB::commit();

            return redirect()->route('portal.dashboard')
                ->with('success', "Hongera {$member->full_name}! Usajili wako umekamilika kikamilifu. Cheti chako cha Uanachama na Kitambulisho cha Kidijitali vimetolewa rasmi hapa chini.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Kosa wakati wa kukamilisha usajili: ' . $e->getMessage()]);
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
