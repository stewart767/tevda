@extends('layouts.app')

@section('title', 'Forgot Password — TEVDA Portal')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-md">
                TEV
            </div>
            <h2 class="text-2xl font-black text-slate-900 font-heading">Reset Your Password</h2>
            <p class="text-xs text-slate-500 mt-1">Enter your registered email address and we'll send you a password reset link.</p>
        </div>

        @include('partials.alerts')

        @if (session('status'))
            <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-xs text-emerald-800 space-y-2">
                <p class="font-bold">{{ session('status') }}</p>
                @if (session('reset_url_preview'))
                    <div class="pt-2 border-t border-emerald-200">
                        <span class="text-[10px] uppercase font-bold text-slate-500 block mb-1">Local Development Quick Reset Link:</span>
                        <a href="{{ session('reset_url_preview') }}" class="font-mono text-[11px] text-emerald-700 underline break-all">
                            Click here to reset password &rarr;
                        </a>
                    </div>
                @endif
            </div>
        @endif

        <form class="mt-8 space-y-5" action="{{ route('password.email') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Registered Email Address</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="user@tevda.or.tz" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition shadow-md cursor-pointer">
                    Send Password Reset Link
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Remember your password?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Back to Login &rarr;</a>
            </p>
        </div>
    </div>
</div>
@endsection
