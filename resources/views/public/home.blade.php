@extends('layouts.app')

@section('title', 'Tanzania Electric Vehicle Drivers Association (TEVDA) — SMART DRIVERS SMART MOBILITY')

@section('content')
@php
    $activeSlides = (isset($sliders) && $sliders->isNotEmpty()) ? $sliders : collect([
        (object)[
            'image_url' => asset('images/slider/slide-1.jpg'),
            'title' => 'Commercial Electric Vehicles in Tanzania',
            'link_url' => null,
        ],
        (object)[
            'image_url' => asset('images/slider/slide-2.jpg'),
            'title' => 'EV Diagnostics & Technician Training',
            'link_url' => null,
        ],
        (object)[
            'image_url' => asset('images/slider/slide-3.jpg'),
            'title' => 'Clean Electric Commercial Transport Fleet',
            'link_url' => null,
        ],
    ]);
@endphp

<!-- Hero Section: Clean Full-Width Panoramic Image Slider (100% Clear, Unobstructed Photos) -->
<section class="relative w-full overflow-hidden h-[380px] sm:h-[500px] lg:h-[580px] bg-slate-950 select-none group border-b-2 border-stone-300"
    x-data="{
        activeSlide: 0,
        slidesCount: {{ $activeSlides->count() }},
        autoplayTimer: null,
        startAutoplay() {
            this.stopAutoplay();
            if (this.slidesCount > 1) {
                this.autoplayTimer = setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
                }, 6000);
            }
        },
        stopAutoplay() {
            if (this.autoplayTimer) {
                clearInterval(this.autoplayTimer);
                this.autoplayTimer = null;
            }
        },
        setSlide(idx) {
            this.activeSlide = idx;
            this.startAutoplay();
        },
        prev() {
            this.activeSlide = (this.activeSlide - 1 + this.slidesCount) % this.slidesCount;
            this.startAutoplay();
        },
        next() {
            this.activeSlide = (this.activeSlide + 1) % this.slidesCount;
            this.startAutoplay();
        }
    }"
    x-init="startAutoplay()"
    @mouseenter="stopAutoplay()"
    @mouseleave="startAutoplay()">

    <!-- SLIDES (100% Crisp, High-Res, Completely Clear & Unobstructed Photos) -->
    @foreach($activeSlides as $index => $slide)
        <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
             :class="activeSlide === {{ $index }} ? 'opacity-100 z-0' : 'opacity-0 -z-10 pointer-events-none'">
            @if(!empty($slide->link_url))
                <a href="{{ $slide->link_url }}" class="block w-full h-full">
                    <img src="{{ $slide->image_url }}" 
                         alt="{{ $slide->title ?? 'TEVDA Slide #'.($index + 1) }}" 
                         class="w-full h-full object-cover object-center">
                </a>
            @else
                <img src="{{ $slide->image_url }}" 
                     alt="{{ $slide->title ?? 'TEVDA Slide #'.($index + 1) }}" 
                     class="w-full h-full object-cover object-center">
            @endif

            @if(!empty($slide->title))
                <div class="absolute bottom-16 sm:bottom-20 left-4 sm:left-12 z-20 max-w-xl">
                    <span class="inline-block bg-[#002040]/85 backdrop-blur-md text-white border border-[#E5B51B]/40 px-4 py-2 rounded-xl text-xs sm:text-sm font-bold shadow-xl">
                        {{ $slide->title }}
                    </span>
                </div>
            @endif
        </div>
    @endforeach

    @if($activeSlides->count() > 1)
        <!-- Floating Navigation Arrows -->
        <button type="button" 
                @click="prev()" 
                class="absolute left-4 sm:left-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-[#002040]/70 hover:bg-[#108010] text-white flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl transition-all duration-200 hover:scale-110 cursor-pointer"
                aria-label="Previous Slide">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" 
                @click="next()" 
                class="absolute right-4 sm:right-8 top-1/2 -translate-y-1/2 z-20 w-11 h-11 sm:w-13 sm:h-13 rounded-full bg-[#002040]/70 hover:bg-[#108010] text-white flex items-center justify-center backdrop-blur-md border border-white/20 shadow-2xl transition-all duration-200 hover:scale-110 cursor-pointer"
                aria-label="Next Slide">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Bottom Indicator Dots -->
        <div class="absolute bottom-5 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2.5 px-4 py-2 rounded-full bg-[#002040]/80 backdrop-blur-md border border-white/10 shadow-lg">
            @foreach($activeSlides as $index => $slide)
                <button type="button" 
                        @click="setSlide({{ $index }})" 
                        class="transition-all duration-300 rounded-full h-2.5 cursor-pointer" 
                        :class="activeSlide === {{ $index }} ? 'w-8 bg-[#108010] shadow-md shadow-[#108010]/50' : 'w-2.5 bg-white/50 hover:bg-white'"></button>
            @endforeach
        </div>
    @endif
</section>



<!-- Stats Ribbon Section -->
<section class="bg-[#FAF7F2] border-b-2 border-stone-300 py-8 relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            <div class="p-4 rounded-2xl bg-white border border-stone-300 shadow-xs">
                <span class="block text-2xl sm:text-3xl font-black text-[#002040] font-heading">31</span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1 block">Regions Covered</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-stone-300 shadow-xs">
                <span class="block text-2xl sm:text-3xl font-black text-[#108010] font-heading">100%</span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1 block">LATRA & Regulatory Compliance</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-stone-300 shadow-xs">
                <span class="block text-2xl sm:text-3xl font-black text-[#E5B51B] font-heading">4</span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1 block">Membership Categories</span>
            </div>
            <div class="p-4 rounded-2xl bg-white border border-stone-300 shadow-xs">
                <span class="block text-2xl sm:text-3xl font-black text-[#002040] font-heading">Zero</span>
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider mt-1 block">Tailpipe Emissions</span>
            </div>
        </div>
    </div>
</section>

<!-- About TEVDA & Executive Welcome Message Section -->
<section class="py-16 lg:py-24 bg-[#FAF7F2] relative overflow-hidden border-b-2 border-stone-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-14">
            <div class="inline-flex items-center gap-2 bg-[#f2fbf2] text-[#108010] border border-[#108010]/30 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-3">
                <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>{{ \App\Models\Setting::get('about_section_badge', 'Executive Welcome & About TEVDA') }}</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#002040] font-heading tracking-tight">
                {{ \App\Models\Setting::get('about_section_title', 'Shaping the Future of Electric Mobility in Tanzania') }}
            </h2>
            <p class="text-slate-600 text-sm sm:text-base leading-relaxed mt-4 font-normal">
                {{ \App\Models\Setting::get('about_section_subtitle', 'Connecting commercial EV drivers, transport operators, financiers, and clean-energy innovators into a unified, sustainable ecosystem.') }}
            </p>
        </div>

        <!-- Executive Welcome Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start mb-16">
            
            <!-- Left: Chairman Executive Profile Card -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-[#002040] rounded-3xl p-6 sm:p-7 text-white shadow-xl border border-[#002c59] relative overflow-hidden group">
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-[#108010]/15 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <!-- Portrait Image Container -->
                    <div class="relative rounded-2xl overflow-hidden mb-6 aspect-square bg-[#001428] border-2 border-[#108010]/50 shadow-md flex items-center justify-center">
                        @if(\App\Models\Setting::hasChairmanPhoto())
                            <img src="{{ \App\Models\Setting::getChairmanPhotoUrl() }}" alt="{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-[#001428] to-[#002040]">
                                <div class="w-20 h-20 rounded-2xl bg-[#108010] text-white font-black text-2xl flex items-center justify-center shadow-lg mb-3">
                                    CM
                                </div>
                                <span class="text-xs font-bold text-slate-300 font-heading uppercase tracking-wider">Dr. Charles Mwansasu</span>
                            </div>
                        @endif

                        <div class="absolute bottom-3 left-3 right-3 bg-[#001428]/90 backdrop-blur-md rounded-xl p-2.5 border border-white/10 flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#E5B51B]">Official Directorate</span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-200">
                                <svg class="w-3.5 h-3.5 text-[#108010]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verified
                            </span>
                        </div>
                    </div>

                    <!-- Chairman Credentials -->
                    <div class="space-y-1.5 border-b border-white/10 pb-5">
                        <span class="text-[11px] font-extrabold text-[#E5B51B] uppercase tracking-widest block font-mono">
                            {{ \App\Models\Setting::get('chairman_role', 'Organization Chair Man') }}
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-white font-heading" style="color: #ffffff !important;">
                            {{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                        </h3>
                        <p class="text-xs text-slate-300 font-medium">
                            {{ \App\Models\Setting::get('chairman_organization', 'Tanzania Electric Vehicles Drivers Association (TEVDA)') }}
                        </p>
                    </div>

                    <!-- Slogan Ribbon -->
                    <div class="pt-4 space-y-4">
                        <div class="p-3.5 bg-[#001428] rounded-2xl border border-white/10 text-xs text-emerald-300 font-semibold flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>{{ \App\Models\Setting::get('chairman_tagline', 'Welcome to TEVDA—Smart Driving, Greener Future.') }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('about') }}" class="w-full text-center bg-[#001428] hover:bg-[#002c59] text-white font-bold text-xs py-2.5 px-3 rounded-xl border border-slate-700 transition">
                                About TEVDA &rarr;
                            </a>
                            <a href="{{ route('leadership') }}" class="w-full text-center bg-[#108010] hover:bg-[#0b570b] text-white font-bold text-xs py-2.5 px-3 rounded-xl transition shadow-md shadow-[#108010]/20">
                                Leadership &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Full Chairman Welcome Message Speech Box -->
            <div class="lg:col-span-8">
                <div class="card-premium rounded-3xl p-7 sm:p-10 border border-slate-200/90 shadow-md relative overflow-hidden bg-white">
                    
                    <!-- Message Header -->
                    <div class="flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-[#f2fbf2] text-[#108010] flex items-center justify-center font-black border border-[#108010]/30">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-[#002040] font-heading">
                                    {{ \App\Models\Setting::get('chairman_message_title', 'Chairman’s Welcome Message') }}
                                </h3>
                                <p class="text-xs text-slate-500">Official Association Address to Members, Partners & Stakeholders</p>
                            </div>
                        </div>

                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 bg-[#f2fbf2] text-[#108010] text-xs font-extrabold rounded-full border border-[#108010]/30 shrink-0">
                            <svg class="w-3.5 h-3.5 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Official Secretariat Release
                        </span>
                    </div>

                    <!-- Welcome Text Paragraphs -->
                    <div class="space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed font-normal">
                        @php
                            $defaultMessage = "On behalf of the Tanzania Electric Vehicles Drivers Association (TEVDA), I warmly welcome our members, partners and stakeholders to our official website.\n\nTEVDA’s vision is to ensure that every Tanzanian can benefit from the transition to electric mobility and clean energy. We represent drivers and operators of all commercial electric vehicles, including electric cars, buses, bajaji and motorcycles.\n\nOur mission is to create opportunities for our members through access to grants, affordable financing, training, technology, employment and investment. We place particular emphasis on empowering young people, women and low-income communities while promoting road safety, environmental protection and professional standards.\n\nWe invite government institutions, development partners, financial institutions, investors, manufacturers and clean-energy companies to work with us in building an inclusive and sustainable electric-mobility ecosystem in Tanzania.\n\nTogether, we can create jobs, reduce transport costs and ensure that no Tanzanian is left behind in this technological transformation.\n\nWelcome to TEVDA—Smart Driving, Greener Future.";
                            $rawMessage = \App\Models\Setting::get('chairman_message', $defaultMessage);
                            $paragraphs = array_filter(array_map('trim', preg_split('/\r\n\r\n|\n\n|\r\r/', $rawMessage)));
                        @endphp

                        @foreach($paragraphs as $index => $paragraph)
                            @if(str_contains(strtolower($paragraph), 'welcome to tevda—smart driving, greener future') || str_contains(strtolower($paragraph), 'welcome to tevda-smart driving, greener future'))
                                <div class="my-5 p-5 bg-[#f2fbf2] border-l-4 border-[#108010] rounded-r-2xl">
                                    <p class="text-base sm:text-lg font-black text-[#002040] font-heading tracking-tight">
                                        {{ $paragraph }}
                                    </p>
                                </div>
                            @elseif($index === 0)
                                <p class="text-[#002040] text-base sm:text-lg font-medium leading-relaxed">
                                    {{ $paragraph }}
                                </p>
                            @else
                                <p>
                                    {{ $paragraph }}
                                </p>
                            @endif
                        @endforeach
                    </div>

                    <!-- Formal Sign-Off Box -->
                    <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-[#002040] text-[#E5B51B] flex items-center justify-center font-black text-sm shadow-md">
                                DR
                            </div>
                            <div>
                                <h4 class="font-black text-base text-[#002040] font-heading">
                                    {{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                                </h4>
                                <p class="text-xs text-[#108010] font-bold">
                                    {{ \App\Models\Setting::get('chairman_role', 'Organization Chair Man') }}
                                </p>
                                <p class="text-[11px] text-slate-500">
                                    {{ \App\Models\Setting::get('chairman_organization', 'Tanzania Electric Vehicles Drivers Association (TEVDA)') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-[#108010] hover:bg-[#0b570b] text-white font-bold text-xs sm:text-sm px-5 py-3 rounded-xl transition shadow-md shadow-[#108010]/20">
                                <span>Become a Member</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-[#002040] font-bold text-xs sm:text-sm px-4 py-3 rounded-xl transition">
                                <span>Contact Secretariat</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</section>

<!-- SMART Principles Section (Core Institutional Pillars) -->
<section class="py-16 lg:py-24 bg-[#FAF7F2] subtle-grid-pattern relative overflow-hidden border-b-2 border-stone-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#108010] bg-[#f2fbf2] px-3.5 py-1.5 rounded-full border border-[#108010]/30 inline-block mb-3">
                {{ \App\Models\Setting::get('smart_principles_badge', 'Operational Philosophy') }}
            </span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-[#002040] font-heading tracking-tight">
                {{ \App\Models\Setting::get('smart_principles_title', 'Our SMART Principles') }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 font-medium">
                {{ \App\Models\Setting::get('smart_principles_subtitle', 'The foundational pillars guiding every TEVDA driver, operator, and operational standard.') }}
            </p>
        </div>

        <!-- 5 Principles Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mb-16">
            
            <!-- S: Safety -->
            <div class="card-premium flex flex-col justify-between group bg-white border border-stone-300 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#108010] text-white font-black text-2xl flex items-center justify-center shadow-md shadow-[#108010]/20 group-hover:scale-110 transition-transform">
                            S
                        </div>
                        <span class="text-[10px] font-extrabold text-[#108010] bg-[#f2fbf2] px-2 py-0.5 rounded-full border border-[#108010]/30">Usalama</span>
                    </div>
                    <h3 class="font-black text-lg text-[#002040] mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_s_title', 'Safety') }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        {{ \App\Models\Setting::get('smart_s_desc', 'Zero preventable accidents through rigorous defensive driving, high-voltage battery operating standards, and pedestrian awareness in silent electric vehicles.') }}
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-200 flex items-center gap-1.5 text-[11px] font-bold text-[#108010]">
                    <svg class="w-3.5 h-3.5 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>Defensive HV Roadcraft</span>
                </div>
            </div>

            <!-- M: Modern Tech -->
            <div class="card-premium flex flex-col justify-between group bg-white border border-stone-300 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#002040] text-[#E5B51B] font-black text-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            M
                        </div>
                        <span class="text-[10px] font-extrabold text-[#002040] bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">Teknolojia</span>
                    </div>
                    <h3 class="font-black text-lg text-[#002040] mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_m_title', 'Modern Tech') }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        {{ \App\Models\Setting::get('smart_m_desc', 'Embracing smart EV powertrains, telemetry, rapid battery swapping networks, smart charging infrastructure, and digital booking tools.') }}
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-200 flex items-center gap-1.5 text-[11px] font-bold text-[#002040]">
                    <svg class="w-3.5 h-3.5 text-[#002040] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>Battery Swapping & IoT</span>
                </div>
            </div>

            <!-- A: Accountability -->
            <div class="card-premium flex flex-col justify-between group bg-white border border-stone-300 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#002040] text-white font-black text-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            A
                        </div>
                        <span class="text-[10px] font-extrabold text-[#002040] bg-slate-100 px-2 py-0.5 rounded-full border border-slate-200">Uwajibikaji</span>
                    </div>
                    <h3 class="font-black text-lg text-[#002040] mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_a_title', 'Accountability') }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        {{ \App\Models\Setting::get('smart_a_desc', 'Financial transparency, strict regulatory compliance with transport authorities (LATRA), and ethical member representation.') }}
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-200 flex items-center gap-1.5 text-[11px] font-bold text-[#002040]">
                    <svg class="w-3.5 h-3.5 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <span>100% LATRA Compliant</span>
                </div>
            </div>

            <!-- R: Respect -->
            <div class="card-premium flex flex-col justify-between group bg-white border border-stone-300 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#E5B51B] text-[#002040] font-black text-2xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            R
                        </div>
                        <span class="text-[10px] font-extrabold text-[#a17c0a] bg-[#fefde8] px-2 py-0.5 rounded-full border border-[#E5B51B]/40">Heshima</span>
                    </div>
                    <h3 class="font-black text-lg text-[#002040] mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_r_title', 'Respect') }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        {{ \App\Models\Setting::get('smart_r_desc', 'Passenger dignity, mutual driver solidarity, ethical business conduct, gender inclusivity, and professional road etiquette.') }}
                    </p>
                </div>
                <div class="pt-3 border-t border-stone-200 flex items-center gap-1.5 text-[11px] font-bold text-[#a17c0a]">
                    <svg class="w-3.5 h-3.5 text-[#E5B51B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    <span>Dignity & Gender Equity</span>
                </div>
            </div>

            <!-- T: Teamwork -->
            <div class="card-premium flex flex-col justify-between group bg-white border border-stone-300 shadow-sm">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#108010] text-white font-black text-2xl flex items-center justify-center shadow-md shadow-[#108010]/20 group-hover:scale-110 transition-transform">
                            T
                        </div>
                        <span class="text-[10px] font-extrabold text-[#108010] bg-[#f2fbf2] px-2 py-0.5 rounded-full border border-[#108010]/30">Umoja</span>
                    </div>
                    <h3 class="font-black text-lg text-[#002040] mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_t_title', 'Teamwork') }}
                    </h3>
                    <p class="text-xs text-slate-600 leading-relaxed mb-4">
                        {{ \App\Models\Setting::get('smart_t_desc', 'Uniting drivers, technicians, energy companies, financiers, and government bodies to build a thriving clean mobility economy.') }}
                    </p>
                </div>
                <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold text-[#108010]">
                    <svg class="w-3.5 h-3.5 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span>Multi-Sector Ecosystem</span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Membership Categories Overview Section -->
<section class="py-16 lg:py-24 bg-[#FAF7F2] border-b-2 border-stone-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#108010] bg-[#f2fbf2] px-3.5 py-1.5 rounded-full border border-[#108010]/30">Member Services</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#002040] font-heading mt-3">Membership Categories & Tiers</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Select the official membership tier suited for your role in Tanzania's commercial EV network.</p>
            </div>
            <a href="{{ route('membership.info') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#108010] hover:text-[#0b570b]">
                <span>All Requirements & Benefits</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($categories as $cat)
                <div class="card-premium flex flex-col justify-between group bg-white border border-stone-300 hover:border-[#108010] transition-all">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="text-[10px] bg-[#f2fbf2] text-[#108010] px-2.5 py-0.5 rounded-full font-bold border border-[#108010]/20">Tier {{ $cat->order_number }}</span>
                            <span class="text-[11px] text-[#002040] font-mono font-bold">{{ $cat->registration_fee > 0 ? number_format($cat->registration_fee) . ' TZS' : 'Verified' }}</span>
                        </div>
                        <h3 class="font-black text-base text-[#002040] font-heading mb-2">{{ $cat->name }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">{{ $cat->description }}</p>
                    </div>
                    <div class="pt-4 border-t border-slate-100">
                        <a href="{{ route('register') }}" class="w-full text-center block bg-[#108010] hover:bg-[#0b570b] text-white text-xs font-bold py-2.5 rounded-xl transition shadow-md shadow-[#108010]/15">
                            Apply for {{ $cat->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Featured Programmes & EV Projects -->
<section class="py-16 lg:py-24 bg-[#FAF7F2] border-b-2 border-stone-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#002040] bg-slate-200/80 px-3.5 py-1.5 rounded-full">Capacity Building</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#002040] font-heading mt-3">Driver Training Programmes</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Accredited curricula in high-voltage safety, defensive driving, and transport entrepreneurship.</p>
            </div>
            <a href="{{ route('programmes') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-[#108010] hover:text-[#0b570b]">
                <span>View All Programmes</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($programmes as $prog)
                <div class="card-premium flex flex-col justify-between bg-white border border-stone-300">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-[#f2fbf2] text-[#108010] flex items-center justify-center font-black text-xs mb-4 border border-[#108010]/30">
                            {{ $prog->code }}
                        </div>
                        <h3 class="font-black text-base text-[#002040] mb-2 font-heading">{{ $prog->title }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">{{ $prog->description }}</p>
                    </div>
                    <a href="{{ route('programmes') }}" class="text-xs font-bold text-[#108010] hover:text-[#0b570b] flex items-center gap-1 mt-2">
                        <span>Course Modules</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            @endforeach
        </div>

    </div>
</section>

<!-- Latest News & Media Releases -->
<section class="py-16 lg:py-24 bg-[#FAF7F2] border-b-2 border-stone-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#108010] bg-[#f2fbf2] px-3.5 py-1.5 rounded-full border border-[#108010]/30">Secretariat Updates</span>
                <h2 class="text-2xl sm:text-3xl font-black text-[#002040] font-heading mt-2">Latest News & Media Releases</h2>
            </div>
            <a href="{{ route('news') }}" class="text-xs sm:text-sm font-bold text-[#108010] hover:text-[#0b570b] flex items-center gap-1">
                <span>View All News</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($news as $item)
                <div class="card-premium p-6 rounded-2xl border border-stone-300 hover:border-[#108010] transition shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between gap-2 mb-2">
                            <span class="text-[10px] uppercase font-extrabold text-[#108010] bg-[#f2fbf2] px-2.5 py-0.5 rounded-md border border-[#108010]/20">{{ ucwords(str_replace('_', ' ', $item->category)) }}</span>
                            <span class="text-[11px] text-slate-400 font-medium">{{ $item->publication_date->format('d M Y') }}</span>
                        </div>
                        <h3 class="font-black text-base text-[#002040] font-heading mb-2">
                            <a href="{{ route('news.show', $item->slug) }}" class="hover:text-[#108010] transition">{{ $item->title }}</a>
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed line-clamp-3 mb-4">{{ $item->summary }}</p>
                    </div>
                    <div class="pt-3 border-t border-stone-100 flex items-center justify-between">
                        <a href="{{ route('news.show', $item->slug) }}" class="text-xs font-bold text-[#108010] hover:text-[#0b570b] inline-flex items-center gap-1">
                            <span>Read Release</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 p-8 text-center text-slate-500 bg-white rounded-2xl border border-dashed border-slate-300 text-xs">
                    Official media releases and announcements from TEVDA Secretariat.
                </div>
            @endforelse
        </div>
    </div>
</section>




@endsection
