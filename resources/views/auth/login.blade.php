@extends('layouts.app')

@section('title', 'Log In — TEVDA Portal')

@section('content')
<div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-slate-100">
    <div class="max-w-md w-full space-y-8 bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl">
        <div class="text-center">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white font-black text-2xl mx-auto mb-4 shadow-md">
                TEV
            </div>
            <h2 class="text-2xl font-black text-slate-900 font-heading">Sign In to TEVDA</h2>
            <p class="text-xs text-slate-500 mt-1">Access the Member Dashboard or Staff Admin Suite</p>
        </div>

        @include('partials.alerts')

        <form class="mt-8 space-y-5" action="{{ route('login.submit') }}" method="POST">
            @csrf

            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email or Phone Number</label>
                <input id="email" name="email" type="text" required value="{{ old('email') }}" placeholder="user@tevda.or.tz or +255..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div>
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Password</label>
                </div>
                <input id="password" name="password" type="password" required placeholder="••••••••" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
            </div>

            <div class="flex items-center justify-between text-xs">
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded">
                    <label for="remember" class="ml-2 text-slate-600">Remember me</label>
                </div>
            </div>

            <div>
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-4 rounded-xl text-sm transition shadow-md">
                    Log In to Portal
                </button>
            </div>
        </form>

        <div class="text-center pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-500">
                New commercial EV driver or partner?
                <a href="{{ route('register') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Create an Account &rarr;</a>
            </p>
        </div>
    </div>
</div>
@endsection
