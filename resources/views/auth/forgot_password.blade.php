@extends('layouts.app')

@section('title', 'Forgot Password — TEVDA Portal')

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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <span class="inline-block px-3 py-1 bg-[#108010]/10 text-[#108010] text-[11px] font-bold uppercase tracking-wider rounded-full mb-2">Account Recovery</span>
                <h2 class="text-2xl font-black text-[#002040] font-heading tracking-tight">Reset Your Password</h2>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Enter your registered email address and we'll send you an encrypted password reset link.</p>
            </div>

            <div class="mt-6">
                @include('partials.alerts')

                @if (session('status'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-900 space-y-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#108010] shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <p class="font-bold">{{ session('status') }}</p>
                        </div>
                        @if (session('reset_url_preview'))
                            <div class="pt-2 border-t border-emerald-200">
                                <span class="text-[10px] uppercase font-bold text-slate-500 block mb-1">Local Development Quick Reset Link:</span>
                                <a href="{{ session('reset_url_preview') }}" class="font-mono text-[11px] text-[#108010] font-semibold underline break-all hover:text-[#0c660c]">
                                    Click here to reset password &rarr;
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <form class="mt-6 space-y-5" action="{{ route('password.email') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="block text-xs font-bold text-[#002040] uppercase tracking-wider mb-1.5">Registered Email Address</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </span>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="member@tevda.or.tz" class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-sm placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white transition">
                    </div>
                </div>

                <div>
                    <button type="submit" class="w-full bg-[#108010] hover:bg-[#0c660c] text-white font-bold py-3.5 px-4 rounded-xl text-sm transition-all shadow-md shadow-[#108010]/20 hover:shadow-lg hover:shadow-[#108010]/30 cursor-pointer flex items-center justify-center gap-2">
                        <span>Send Password Reset Link</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </button>
                </div>
            </form>

            <div class="text-center pt-6 mt-6 border-t border-slate-100">
                <p class="text-xs text-slate-500">
                    Remembered your password?
                    <a href="{{ route('login') }}" class="font-bold text-[#108010] hover:text-[#002040] transition">Back to Sign In &rarr;</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center text-xs text-slate-400">
            Protected by TEVDA Secure Portal Encryption
        </div>
    </div>
</div>
@endsection
