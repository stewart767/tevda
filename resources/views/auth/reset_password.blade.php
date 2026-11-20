@extends('layouts.app')

@section('title', 'Set New Password — TEVDA Portal')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-md">
                TEV
            </div>
            <h2 class="text-2xl font-black text-slate-900 font-heading">Choose a New Password</h2>
            <p class="text-xs text-slate-500 mt-1">Please enter your email and set your new password.</p>
        </div>

        @include('partials.alerts')

        <form class="mt-8 space-y-5" action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address</label>
                <input id="email" name="email" type="email" required value="{{ old('email', $email) }}" placeholder="user@tevda.or.tz" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">New Password</label>
                <input id="password" name="password" type="password" required placeholder="Minimum 6 characters" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm New Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Re-enter new password" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition shadow-md cursor-pointer">
                    Save New Password & Sign In
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Cancel password reset?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Return to Login &rarr;</a>
            </p>
        </div>
    </div>
</div>
@endsection
