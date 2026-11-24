@extends('layouts.app')

@section('title', 'Member & Staff Login — TEVDA Portal')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center py-10 px-4 sm:px-6 lg:px-8 bg-slate-50">
    <div class="max-w-4xl w-full bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-12">
        
        <!-- Left Side: Electric Mobility Showcase & Branding (Visible on LG screens) -->
        <div class="lg:col-span-5 relative bg-[#002040] text-white p-8 lg:p-10 flex flex-col justify-between overflow-hidden min-h-[320px] lg:min-h-full">
            <!-- Background Image with Brand Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="{{ asset('images/slider/slide-3.jpg') }}" alt="TEVDA Electric Vehicles" class="w-full h-full object-cover opacity-35 transform scale-105">
                <div class="absolute inset-0 bg-gradient-to-b from-[#002040]/90 via-[#002040]/80 to-[#001428]/95"></div>
            </div>

            <!-- Content Header -->
            <div class="relative z-10 space-y-4">
                <div class="inline-flex items-center gap-2 bg-[#001428]/90 border border-[#108010]/80 px-3.5 py-1.5 rounded-full text-xs font-black tracking-wider text-[#E5B51B] shadow-md backdrop-blur-sm">
                    <span class="w-2 h-2 rounded-full bg-[#108010] animate-pulse"></span>
                    <span>SMART MOBILITY PORTAL</span>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black font-heading text-white tracking-tight leading-snug">
                    Driving Tanzania Towards a <span class="text-[#E5B51B]">Greener Future.</span>
                </h1>

                <p class="text-xs sm:text-sm text-slate-200 leading-relaxed font-normal">
                    Secure access for registered commercial electric vehicle drivers, transport operators, and authorized secretariat staff.
                </p>
            </div>

            <!-- Features Highlights in Left Panel -->
            <div class="relative z-10 pt-8 border-t border-white/10 space-y-3 text-xs text-slate-200">
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#108010] flex items-center justify-center shrink-0 text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Digital Membership Smart Card & QR Badge</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#108010] flex items-center justify-center shrink-0 text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Certified Driver Training & High-Voltage Skills</span>
                </div>
                <div class="flex items-center gap-2.5">
                    <div class="w-6 h-6 rounded-lg bg-[#108010] flex items-center justify-center shrink-0 text-white">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <span>Asset Financing, Grants & Commercial Fleet Jobs</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Clean Authentication Form -->
        <div class="lg:col-span-7 p-8 sm:p-10 lg:p-12 bg-white flex flex-col justify-between">
            <div>
                <!-- Brand Header -->
                <div class="mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-12 h-12 rounded-2xl bg-[#002040] border-2 border-[#108010] flex items-center justify-center text-white font-black text-xl shadow-md">
                            <span class="tracking-tighter text-[#E5B51B]">TEV</span>
                        </div>
                        <div>
                            <span class="text-lg font-black tracking-tight text-[#002040] block font-heading leading-tight">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} PORTAL</span>
                            <span class="text-[9px] uppercase font-bold tracking-wider text-[#108010] block">{{ __('Member & Administration Sign In') }}</span>
                        </div>
                    </div>
                    <h2 class="text-2xl font-black text-[#002040] font-heading">Sign In to Your Account</h2>
                    <p class="text-xs text-slate-500 mt-1">Enter your registered email address or mobile phone number to continue.</p>
                </div>

                @include('partials.alerts')

                <form class="space-y-4" action="{{ route('login.submit') }}" method="POST">
                    @csrf

                    <div>
                        <label for="email" class="block text-xs font-bold text-[#002040] uppercase tracking-wider mb-1.5">
                            Email or Mobile Phone Number <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <input id="email" 
                                   name="email" 
                                   type="text" 
                                   required 
                                   value="{{ old('email') }}" 
                                   placeholder="user@tevda.or.tz or 0757 700 401" 
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-xs sm:text-sm text-[#002040] focus:outline-hidden focus:border-[#108010] focus:ring-2 focus:ring-[#108010]/20 focus:bg-white transition">
                        </div>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <label for="password" class="block text-xs font-bold text-[#002040] uppercase tracking-wider">
                                Password <span class="text-rose-500">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#108010] hover:text-[#0b570b] transition">
                                Forgot password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" 
                                   name="password" 
                                   type="password" 
                                   required 
                                   placeholder="••••••••" 
                                   class="w-full bg-slate-50 border border-slate-300 rounded-xl pl-10 pr-4 py-3 text-xs sm:text-sm text-[#002040] focus:outline-hidden focus:border-[#108010] focus:ring-2 focus:ring-[#108010]/20 focus:bg-white transition">
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center cursor-pointer select-none">
                            <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-[#108010] focus:ring-[#108010] border-slate-300 rounded cursor-pointer">
                            <span class="ml-2 text-slate-600 font-medium">Remember my credentials</span>
                        </label>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#108010] hover:bg-[#0b570b] text-white font-extrabold py-3.5 px-4 rounded-xl text-sm transition shadow-lg shadow-[#108010]/25 flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                            <span>Sign In to TEVDA Portal</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer in Login Box -->
            <div class="text-center pt-6 mt-6 border-t border-slate-100 space-y-2">
                <p class="text-xs text-slate-500">
                    New commercial electric vehicle driver or operator?
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 font-extrabold text-[#108010] hover:text-[#0b570b] text-xs">
                    <span>Register for TEVDA Membership</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
