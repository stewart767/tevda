@extends('layouts.app')

@section('title', 'Driver Training Programmes & Certification — TEVDA')

@section('content')
<!-- Page Header Banner -->
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden border-b-2 border-stone-300">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-[#E5B51B] bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-[#E5B51B]/30">
            <svg class="w-3.5 h-3.5 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            <span>{{ __('National Capacity Building') }}</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white tracking-tight" style="color: #ffffff !important;">{{ __('Driver Training Programmes') }}</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">{{ __('Standardized national curricula empowering commercial EV drivers with high-voltage battery mastery, defensive road safety, and micro-fleet enterprise skills.') }}</p>
    </div>
</div>

<!-- Main Programmes Section (Cream Background with Crisp Bordered Cards) -->
<div class="py-16 bg-[#FAF7F2] border-b-2 border-stone-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Programmes List -->
        <div class="space-y-12">
            @foreach ($programmes as $prog)
                <div class="card-premium bg-white rounded-3xl p-7 sm:p-9 border border-stone-300 shadow-sm hover:shadow-md transition">
                    
                    <!-- Programme Header -->
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8 pb-6 border-b border-stone-200">
                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-xs font-extrabold uppercase text-[#108010] bg-[#f2fbf2] px-3 py-1 rounded-full border border-[#108010]/30">Programme {{ $prog->code }}</span>
                                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">• Accredited Curriculum</span>
                            </div>
                            <h2 class="text-2xl sm:text-3xl font-black text-[#002040] font-heading">{{ $prog->title }}</h2>
                            <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">{{ $prog->description }}</p>
                        </div>
                        
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 w-full lg:w-auto">
                            @if ($prog->objective)
                                <div class="p-4 bg-[#FAF7F2] rounded-2xl border border-stone-200 text-xs text-slate-700 max-w-sm">
                                    <strong class="text-[#002040] block mb-1 font-bold">Curriculum Objective:</strong>
                                    {{ $prog->objective }}
                                </div>
                            @endif

                            @auth
                                <a href="{{ route('portal.training') }}" class="inline-flex items-center justify-center gap-2 bg-[#108010] hover:bg-[#0b570b] text-white font-extrabold px-5 py-3 rounded-xl shadow-md transition text-xs sm:text-sm whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>{{ __('Portal Enrolment') }}</span>
                                </a>
                            @else
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 bg-[#108010] hover:bg-[#0b570b] text-white font-extrabold px-5 py-3 rounded-xl shadow-md transition text-xs sm:text-sm whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>{{ __('Registration Portal') }}</span>
                                </a>
                            @endauth
                        </div>
                    </div>

                    <!-- Courses & Modules Container -->
                    <div class="space-y-8">
                        @foreach ($prog->courses as $course)
                            <div class="bg-[#FAF7F2] p-6 sm:p-7 rounded-2xl border border-stone-300 shadow-2xs">
                                
                                <!-- Course Header & Direct Registration Portal Button -->
                                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-4 pb-4 border-b border-stone-200">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-[10px] font-extrabold uppercase text-[#108010] bg-[#f2fbf2] px-2.5 py-0.5 rounded-full border border-[#108010]/30">Course {{ $loop->iteration }}</span>
                                            <span class="inline-flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                                                <span class="w-2 h-2 rounded-full bg-[#108010] animate-pulse"></span>
                                                {{ __('Registration Open') }}
                                            </span>
                                        </div>
                                        <h3 class="text-lg sm:text-xl font-black text-[#002040] font-heading">{{ $course->title }}</h3>
                                    </div>
                                    
                                    <!-- Course Badges & Registration Action -->
                                    <div class="flex flex-wrap items-center gap-2.5 sm:gap-3">
                                        <span class="bg-white px-3 py-1.5 rounded-xl border border-stone-200 text-xs text-slate-600 font-semibold shadow-2xs">
                                            Duration: <strong class="text-[#002040]">{{ $course->duration_hours }} Hours</strong>
                                        </span>
                                        <span class="bg-[#f2fbf2] text-[#108010] px-3 py-1.5 rounded-xl border border-[#108010]/30 text-xs font-bold shadow-2xs">
                                            Pass Mark: <strong>{{ $course->pass_mark_percentage }}%</strong>
                                        </span>
                                        
                                        @auth
                                            <a href="{{ route('portal.training') }}" class="inline-flex items-center gap-1.5 bg-[#108010] hover:bg-[#0b570b] text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs hover:shadow-md cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>{{ __('Enrol via Portal') }}</span>
                                            </a>
                                        @else
                                            <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-[#108010] hover:bg-[#0b570b] text-white text-xs font-extrabold px-4 py-2 rounded-xl transition shadow-xs hover:shadow-md cursor-pointer">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                <span>{{ __('Portal of Registration') }}</span>
                                            </a>
                                        @endauth
                                    </div>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-600 mb-5 leading-relaxed">{{ $course->description }}</p>

                                <!-- Modules Grid -->
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach ($course->modules as $mod)
                                        <div class="p-4 bg-white rounded-2xl border border-stone-200/90 flex flex-col justify-between shadow-2xs hover:border-[#108010]/40 transition">
                                            <div>
                                                <span class="text-[10px] font-extrabold text-[#108010] uppercase block mb-1">Module {{ $mod->order_number }} • {{ $mod->duration_hours }}h</span>
                                                <h4 class="text-xs font-bold text-[#002040] mb-1 leading-snug">{{ $mod->title }}</h4>
                                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $mod->description }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Dedicated Bottom Portal of Registration Banner for Every Course -->
                                <div class="mt-5 pt-4 border-t border-stone-200/80 flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-white p-4 rounded-xl border border-stone-300">
                                    <div class="flex items-center gap-2.5 text-xs text-slate-700">
                                        <div class="w-8 h-8 rounded-lg bg-[#f2fbf2] text-[#108010] flex items-center justify-center shrink-0 border border-[#108010]/30">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                        </div>
                                        <div>
                                            <strong class="text-[#002040] block font-heading">{{ __('Accredited EV Certification Course') }}</strong>
                                            <span class="text-slate-500 text-[11px]">{{ __('Complete this course to obtain recognized LATRA-compliant certification and smart driver ID credentials.') }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 shrink-0">
                                        @auth
                                            <a href="{{ route('portal.training') }}" class="inline-flex items-center gap-1.5 bg-[#002040] hover:bg-[#002c59] text-white text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-xs">
                                                <span>{{ __('Enrol via Member Portal') }}</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        @else
                                            <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-[#108010] hover:bg-[#0b570b] text-white text-xs font-black px-4 py-2.5 rounded-xl transition shadow-xs">
                                                <span>{{ __('Register for this Course') }}</span>
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                            <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-[#002040] hover:text-[#108010] text-xs font-bold px-3.5 py-2.5 rounded-xl border border-stone-300 bg-[#FAF7F2] hover:bg-slate-100 transition">
                                                <span>{{ __('Portal Login') }}</span>
                                            </a>
                                        @endauth
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                </div>
            @endforeach
        </div>

        <!-- Global Bottom Enrolment CTA Card -->
        <div class="bg-[#002040] rounded-3xl p-8 sm:p-12 text-white text-center shadow-xl border border-[#002c59] relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>

            <h3 class="text-2xl sm:text-3xl font-black font-heading mb-3 text-white relative z-10" style="color: #ffffff !important;">{{ __('Join Official TEVDA Training Cohorts') }}</h3>
            <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto mb-6 leading-relaxed relative z-10">
                {{ __('Active TEVDA members can register for accredited training sessions across all 31 regions through the Member Portal and receive verifiable digital completion certificates.') }}
            </p>
            <div class="flex flex-wrap justify-center gap-4 relative z-10">
                <a href="{{ route('register') }}" class="bg-[#108010] hover:bg-[#0b570b] text-white font-extrabold px-8 py-3.5 rounded-xl text-xs sm:text-sm transition-all shadow-md shadow-[#108010]/30 cursor-pointer">
                    {{ __('Open Registration Portal') }}
                </a>
                <a href="{{ route('login') }}" class="bg-white/10 hover:bg-white text-white hover:text-[#002040] font-bold px-8 py-3.5 rounded-xl text-xs sm:text-sm border border-white/40 transition">
                    {{ __('Member Login') }}
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
