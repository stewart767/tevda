@extends('layouts.app')

@section('title', 'Set New Password — TEVDA Portal')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-50 relative overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-[#002040]/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-[#108010]/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10">
        <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200/80 shadow-xl shadow-slate-200/50">
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl bg-[#002040] border-2 border-[#E5B51B]/30 flex items-center justify-center mx-auto mb-4 shadow-lg shadow-[#002040]/20">
                    <svg class="w-8 h-8 text-[#E5B51B]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 bg-[#108010]/10 text-[#108010] text-[11px] font-bold uppercase tracking-wider rounded-full mb-2">Password Update</span>
                <h2 class="text-2xl font-black text-[#002040] font-heading tracking-tight">Create New Password</h2>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Choose a strong password to protect your TEVDA driver and operator portal account.</p>
            </div>

            <div class="mt-6">
                @include('partials.alerts')
            </div>

            <form class="mt-6 space-y-5" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-xs font-bold text-[#002040] uppercase tracking-wider mb-1.5">Email Address</label>
                    <input id="email" name="email" type="email" required value="{{ old('email', $email) }}" placeholder="user@tevda.or.tz" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-bold text-[#002040] uppercase tracking-wider mb-1.5">New Password</label>
                    <input id="password" name="password" type="password" required placeholder="Minimum 6 characters" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white transition">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-[#002040] uppercase tracking-wider mb-1.5">Confirm New Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Re-enter new password" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white transition">
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#108010] hover:bg-[#0c660c] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-md shadow-[#108010]/20 hover:shadow-lg hover:shadow-[#108010]/30 cursor-pointer flex items-center justify-center gap-2">
                        <span>Save Password & Sign In</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="text-center pt-6 mt-6 border-t border-slate-100">
                <p class="text-xs text-slate-500">
                    Cancel password reset?
                    <a href="{{ route('login') }}" class="font-bold text-[#108010] hover:text-[#002040] transition">Return to Login &rarr;</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
