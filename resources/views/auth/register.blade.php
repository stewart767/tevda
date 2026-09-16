@extends('layouts.app')

@section('title', 'Join TEVDA — Register Account')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-md">
                TEV
            </div>
            <h2 class="text-2xl font-black text-slate-900 font-heading">Join TEVDA</h2>
            <p class="text-xs text-slate-500 mt-1">SMART DRIVERS SMART MOBILITY</p>
        </div>

        @include('partials.alerts')

        <form class="mt-8 space-y-4" action="{{ route('register.submit') }}" method="POST">
            @csrf

            <div>
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name <span class="text-rose-500">*</span></label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}" placeholder="As shown on NIDA / Driving Licence" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone Number <span class="text-rose-500">*</span></label>
                <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" placeholder="+255..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}" placeholder="name@example.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password (min 8 characters) <span class="text-rose-500">*</span></label>
                <input id="password" name="password" type="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Confirm Password <span class="text-rose-500">*</span></label>
                <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div class="flex items-start gap-2 pt-2">
                <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded mt-0.5">
                <label for="terms" class="text-xs text-slate-600 leading-snug">
                    I agree to the <a href="{{ route('terms') }}" target="_blank" class="text-emerald-700 font-semibold underline">Terms of Use</a> and <a href="{{ route('privacy') }}" target="_blank" class="text-emerald-700 font-semibold underline">Privacy Policy</a>.
                </label>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition shadow-md">
                    Create Account & Continue Application
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                Already registered?
                <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Log In to Portal &rarr;</a>
            </p>
        </div>
    </div>
</div>
@endsection
