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

    public function showRegister()
    {
        if (Auth::check()) {
            return $this->authenticatedRedirect(Auth::user());
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'terms' => 'accepted',
        ]);

        $memberRole = Role::where('slug', 'member')->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => $memberRole?->id,
            'is_active' => true,
        ]);

        Auth::login($user);
        AuditLog::log('register', 'auth', (string)$user->id, null, ['email' => $user->email]);

        return redirect()->route('membership.apply')->with('success', 'Account created successfully! Please complete your membership application.');
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
