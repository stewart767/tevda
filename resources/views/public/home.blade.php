@extends('layouts.app')

@section('title', 'Tanzania Electric Vehicle Drivers Association (TEVDA) — SMART DRIVERS SMART MOBILITY')

@section('content')
<!-- Hero Section -->
<section class="relative hero-pattern text-white overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                <!-- Slogan Badge -->
                <div class="inline-flex items-center gap-2 bg-emerald-500/15 border border-emerald-400/30 px-4 py-2 rounded-full text-emerald-300 text-xs sm:text-sm font-extrabold tracking-wider uppercase backdrop-blur-md shadow-xs animate-pulse-subtle">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>SMART DRIVERS SMART MOBILITY</span>
                </div>

                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black tracking-tight text-white font-heading leading-tight">
                    Empowering Tanzanians Through <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">Electric Mobility</span>
                </h1>

                <p class="text-sm sm:text-base lg:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-normal">
                    {{ \App\Models\Setting::get('hero_supporting_text', 'TEVDA represents drivers and operators of commercially used electric vehicles. We connect members with training, employment, business opportunities, capital, grants, affordable loans and partnerships that help them participate in Tanzania’s clean-energy economy.') }}
                </p>

                <!-- Hero CTAs -->
                <div class="flex flex-col sm:flex-row gap-3.5 justify-center lg:justify-start pt-2">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm sm:text-base px-8 py-4 rounded-2xl shadow-xl hover:shadow-emerald-500/30 transition transform hover:-translate-y-0.5">
                        <span>Join TEVDA Today</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="{{ route('opportunities') }}" class="inline-flex items-center justify-center gap-2.5 bg-slate-900/80 hover:bg-slate-800 text-white border border-slate-700/80 font-bold text-sm sm:text-base px-7 py-4 rounded-2xl backdrop-blur-md transition hover:border-emerald-500/50">
                        <span>Explore Opportunities</span>
                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </a>
                </div>

                <!-- Quick Highlights Metrics -->
                <div class="pt-6 grid grid-cols-3 gap-4 border-t border-slate-800/80 text-left">
                    <div class="bg-slate-900/40 p-3.5 rounded-2xl border border-slate-800/60 backdrop-blur-sm">
                        <span class="block text-2xl sm:text-3xl font-black text-emerald-400 font-heading">100%</span>
                        <span class="text-[11px] sm:text-xs text-slate-300 font-medium leading-tight">Clean Commercial Mobility</span>
                    </div>
                    <div class="bg-slate-900/40 p-3.5 rounded-2xl border border-slate-800/60 backdrop-blur-sm">
                        <span class="block text-2xl sm:text-3xl font-black text-cyan-400 font-heading">6+</span>
                        <span class="text-[11px] sm:text-xs text-slate-300 font-medium leading-tight">Certified Training Tracks</span>
                    </div>
                    <div class="bg-slate-900/40 p-3.5 rounded-2xl border border-slate-800/60 backdrop-blur-sm">
                        <span class="block text-2xl sm:text-3xl font-black text-amber-400 font-heading">Digital</span>
                        <span class="text-[11px] sm:text-xs text-slate-300 font-medium leading-tight">QR ID & Certificates</span>
                    </div>
                </div>
            </div>

            <!-- Hero Graphic / Quick Verification Card -->
            <div class="lg:col-span-5">
                <div class="glass-panel-dark rounded-3xl p-6 sm:p-8 shadow-2xl border border-emerald-500/30 relative overflow-hidden">
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-emerald-500/15 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-800">
                        <div class="flex items-center gap-3">
                            @if(\App\Models\Setting::hasCustomLogo())
                                <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-9 w-auto max-w-[100px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center font-black text-white text-xs">TEV</div>
                            @else
                                <div class="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center font-black text-white text-xs">TEV</div>
                            @endif
                            <div>
                                <h2 class="text-sm font-black text-white uppercase tracking-wider font-heading">Instant Verification</h2>
                                <p class="text-[11px] text-slate-400">Official Association Public Registry</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-950 text-emerald-300 border border-emerald-800">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> LIVE
                        </span>
                    </div>

                    <!-- Verification Tabs -->
                    <div x-data="{ activeTab: 'member' }">
                        <div class="flex bg-slate-900/90 p-1.5 rounded-2xl mb-5 border border-slate-800">
                            <button type="button" @click="activeTab = 'member'" :class="activeTab === 'member' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white'" class="flex-1 py-2 text-xs font-bold rounded-xl transition duration-150">
                                Verify Member ID
                            </button>
                            <button type="button" @click="activeTab = 'certificate'" :class="activeTab === 'certificate' ? 'bg-cyan-600 text-white shadow-md' : 'text-slate-400 hover:text-white'" class="flex-1 py-2 text-xs font-bold rounded-xl transition duration-150">
                                Verify Certificate
                            </button>
                        </div>

                        <!-- Member Form -->
                        <div x-show="activeTab === 'member'" x-transition>
                            <form action="{{ route('verify.membership') }}" method="GET" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Enter Membership Number</label>
                                    <input type="text" name="number" placeholder="e.g. TEVDA-2026-00001" required class="w-full bg-slate-950/80 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white placeholder-slate-500 font-mono focus:outline-hidden focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition">
                                </div>
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-3.5 px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-600/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span>Verify Driver Status</span>
                                </button>
                            </form>
                        </div>

                        <!-- Certificate Form -->
                        <div x-show="activeTab === 'certificate'" x-transition>
                            <form action="{{ route('verify.certificate') }}" method="GET" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 mb-1.5">Enter Certificate Serial Number</label>
                                    <input type="text" name="number" placeholder="e.g. TEVDA-CERT-2026-000001" required class="w-full bg-slate-950/80 border border-slate-700/80 rounded-xl px-4 py-3 text-xs sm:text-sm text-white placeholder-slate-500 font-mono focus:outline-hidden focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                                </div>
                                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-black py-3.5 px-4 rounded-xl text-xs sm:text-sm transition flex items-center justify-center gap-2 shadow-lg shadow-cyan-600/20">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span>Verify Certificate Authenticity</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-400 mt-4 text-center">
                        QR codes printed on digital membership cards and certificates scan directly to verified status records.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- About TEVDA & Chairman's Welcome Message Section -->
<section class="py-20 lg:py-28 bg-slate-50 relative overflow-hidden border-b border-slate-200/80">
    <!-- Ambient Background Glows -->
    <div class="absolute -left-20 top-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 bottom-10 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-16">
            <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-800 border border-emerald-300/80 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest mb-3 shadow-xs">
                <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>{{ \App\Models\Setting::get('about_section_badge', 'Executive Welcome & About TEVDA') }}</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 font-heading tracking-tight">
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
                <div class="bg-slate-950 rounded-3xl p-6 sm:p-7 text-white shadow-2xl border border-slate-800 relative overflow-hidden group">
                    <div class="absolute -right-12 -top-12 w-48 h-48 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <!-- Portrait Image Container -->
                    <div class="relative rounded-2xl overflow-hidden mb-6 aspect-square bg-slate-900 border-2 border-emerald-500/40 shadow-xl flex items-center justify-center">
                        @if(\App\Models\Setting::hasChairmanPhoto())
                            <img src="{{ \App\Models\Setting::getChairmanPhotoUrl() }}" alt="{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center p-6 text-center bg-gradient-to-br from-emerald-950 to-slate-900">
                                <div class="w-20 h-20 rounded-2xl bg-emerald-600 text-white font-black text-2xl flex items-center justify-center shadow-lg mb-3">
                                    CM
                                </div>
                                <span class="text-xs font-bold text-slate-300 font-heading uppercase tracking-wider">Dr. Charles Mwansasu</span>
                            </div>
                        @endif

                        <div class="absolute bottom-3 left-3 right-3 bg-slate-950/85 backdrop-blur-md rounded-xl p-2.5 border border-slate-800/80 flex items-center justify-between">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-400">Official Directorate</span>
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-slate-300">
                                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Verified
                            </span>
                        </div>
                    </div>

                    <!-- Chairman Credentials -->
                    <div class="space-y-1.5 border-b border-slate-800 pb-5">
                        <span class="text-[11px] font-extrabold text-emerald-400 uppercase tracking-widest block font-mono">
                            {{ \App\Models\Setting::get('chairman_role', 'Organization Chair Man') }}
                        </span>
                        <h3 class="text-xl sm:text-2xl font-black text-white font-heading">
                            {{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                        </h3>
                        <p class="text-xs text-slate-400 font-medium">
                            {{ \App\Models\Setting::get('chairman_organization', 'Tanzania Electric Vehicles Drivers Association (TEVDA)') }}
                        </p>
                    </div>

                    <!-- Slogan Ribbon -->
                    <div class="pt-4 space-y-4">
                        <div class="p-3.5 bg-slate-900/90 rounded-2xl border border-slate-800 text-xs text-emerald-300 font-semibold flex items-center gap-2.5">
                            <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>{{ \App\Models\Setting::get('chairman_tagline', 'Welcome to TEVDA—Smart Driving, Greener Future.') }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-2 pt-1">
                            <a href="{{ route('about') }}" class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs py-2.5 px-3 rounded-xl border border-slate-700/80 transition">
                                About TEVDA &rarr;
                            </a>
                            <a href="{{ route('leadership') }}" class="w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs py-2.5 px-3 rounded-xl transition shadow-md shadow-emerald-600/20">
                                Leadership &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Full Chairman Welcome Message Speech Box -->
            <div class="lg:col-span-8">
                <div class="glass-card rounded-3xl p-7 sm:p-10 lg:p-12 border border-slate-200/80 shadow-xl relative overflow-hidden bg-white">
                    
                    <!-- Decorative Large Watermark Quote -->
                    <div class="absolute right-6 top-6 text-slate-100 select-none pointer-events-none font-serif text-8xl lg:text-9xl leading-none opacity-80">
                        &rdquo;
                    </div>

                    <!-- Message Header -->
                    <div class="relative z-10 flex items-center justify-between gap-4 mb-6 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black border border-emerald-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-slate-900 font-heading">
                                    {{ \App\Models\Setting::get('chairman_message_title', 'Chairman’s Welcome Message') }}
                                </h3>
                                <p class="text-xs text-slate-500">Official Association Address to Members, Partners & Stakeholders</p>
                            </div>
                        </div>

                        <span class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-800 text-xs font-extrabold rounded-full border border-emerald-200/80 shrink-0">
                            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Official Secretariat Release
                        </span>
                    </div>

                    <!-- Welcome Text Paragraphs -->
                    <div class="relative z-10 space-y-4 text-slate-700 text-sm sm:text-base leading-relaxed font-normal">
                        @php
                            $defaultMessage = "On behalf of the Tanzania Electric Vehicles Drivers Association (TEVDA), I warmly welcome our members, partners and stakeholders to our official website.\n\nTEVDA’s vision is to ensure that every Tanzanian can benefit from the transition to electric mobility and clean energy. We represent drivers and operators of all commercial electric vehicles, including electric cars, buses, bajaji and motorcycles.\n\nOur mission is to create opportunities for our members through access to grants, affordable financing, training, technology, employment and investment. We place particular emphasis on empowering young people, women and low-income communities while promoting road safety, environmental protection and professional standards.\n\nWe invite government institutions, development partners, financial institutions, investors, manufacturers and clean-energy companies to work with us in building an inclusive and sustainable electric-mobility ecosystem in Tanzania.\n\nTogether, we can create jobs, reduce transport costs and ensure that no Tanzanian is left behind in this technological transformation.\n\nWelcome to TEVDA—Smart Driving, Greener Future.";
                            $rawMessage = \App\Models\Setting::get('chairman_message', $defaultMessage);
                            $paragraphs = array_filter(array_map('trim', preg_split('/\r\n\r\n|\n\n|\r\r/', $rawMessage)));
                        @endphp

                        @foreach($paragraphs as $index => $paragraph)
                            @if(str_contains(strtolower($paragraph), 'welcome to tevda—smart driving, greener future') || str_contains(strtolower($paragraph), 'welcome to tevda-smart driving, greener future'))
                                <div class="my-5 p-5 bg-gradient-to-r from-emerald-500/10 via-teal-500/10 to-cyan-500/10 border-l-4 border-emerald-500 rounded-r-2xl">
                                    <p class="text-base sm:text-lg font-black text-emerald-950 font-heading tracking-tight">
                                        {{ $paragraph }}
                                    </p>
                                </div>
                            @elseif($index === 0)
                                <p class="text-slate-800 text-base sm:text-lg font-medium leading-relaxed">
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
                    <div class="relative z-10 mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-emerald-400 flex items-center justify-center font-black text-sm shadow-md">
                                DR
                            </div>
                            <div>
                                <h4 class="font-black text-base text-slate-900 font-heading">
                                    {{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                                </h4>
                                <p class="text-xs text-emerald-700 font-bold">
                                    {{ \App\Models\Setting::get('chairman_role', 'Organization Chair Man') }}
                                </p>
                                <p class="text-[11px] text-slate-500">
                                    {{ \App\Models\Setting::get('chairman_organization', 'Tanzania Electric Vehicles Drivers Association (TEVDA)') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm px-5 py-3 rounded-xl transition shadow-lg shadow-emerald-600/20">
                                <span>Join Association</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-1.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs sm:text-sm px-4 py-3 rounded-xl transition">
                                <span>Contact</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <!-- 4 Key Strategic Pillars Highlight (From the Chairman's Welcome Vision) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-4">
            
            <!-- Pillar 1 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-emerald-500/60 transition bg-white flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black mb-4 border border-emerald-200/80 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h4 class="font-black text-base text-slate-900 font-heading mb-2">All Commercial EVs</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Unifying operators of electric cars, buses, bajaji (tricycles), and motorcycles under standard regulatory representation.
                    </p>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-cyan-500/60 transition bg-white flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-700 flex items-center justify-center font-black mb-4 border border-cyan-200/80 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="font-black text-base text-slate-900 font-heading mb-2">Capital & Grants Access</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Connecting verified members to affordable asset financing, green development grants, technology tools, and investments.
                    </p>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-indigo-500/60 transition bg-white flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-black mb-4 border border-indigo-200/80 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <h4 class="font-black text-base text-slate-900 font-heading mb-2">Youth & Women Inclusion</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Targeted initiatives empowering youth, women drivers, and low-income communities into clean-energy transport entrepreneurship.
                    </p>
                </div>
            </div>

            <!-- Pillar 4 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-amber-500/60 transition bg-white flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-black mb-4 border border-amber-200/80 shadow-xs">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h4 class="font-black text-base text-slate-900 font-heading mb-2">Safety & Certification</h4>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Defensive EV driving, high-voltage battery handling, environmental protection, and accredited professional credentials.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- Institutional Profile & SMART Principles Section -->
<section class="py-20 lg:py-28 bg-white subtle-grid-pattern relative overflow-hidden">
    <!-- Ambient Background Glows -->
    <div class="absolute -left-20 top-20 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 bottom-20 w-80 h-80 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Section Header: Institutional Profile & Who We Are -->
        <div class="text-center max-w-4xl mx-auto mb-16 space-y-4">
            <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200/80 px-4 py-1.5 rounded-full text-xs font-black uppercase tracking-widest shadow-xs">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>{{ \App\Models\Setting::get('who_we_are_badge', 'Institutional Profile & Mandate') }}</span>
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 font-heading tracking-tight">
                {{ \App\Models\Setting::get('who_we_are_title', 'Who We Are') }}
            </h2>
            <p class="text-slate-700 text-base sm:text-lg leading-relaxed font-medium max-w-3xl mx-auto">
                {{ \App\Models\Setting::get('who_we_are_description', 'The Tanzania Electric Vehicle Drivers Association (TEVDA) is the premier national apex body organizing, upskilling, and advocating for commercial electric vehicle operators, charging pioneers, and sustainable fleet entrepreneurs across Tanzania.') }}
            </p>
            <p class="text-slate-500 text-xs sm:text-sm leading-relaxed max-w-2xl mx-auto font-normal">
                {{ \App\Models\Setting::get('who_we_are_subtext', 'Empowering drivers of electric cars, tricycles (bajaji), motorcycles (bodaboda), and clean-mobility fleets across all regions of Tanzania with accredited safety certification, green financing, and statutory representation.') }}
            </p>
        </div>

        <!-- 4 Core Institutional Pillars Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-20">
            <!-- Pillar 1 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-emerald-500/60 transition bg-white flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black mb-4 border border-emerald-200/80 shadow-xs group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-md inline-block mb-2">Advocacy & Policy</span>
                    <h3 class="font-black text-base text-slate-900 font-heading mb-2">Statutory Representation</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Championing fair tariffs, designated EV parking and charging bays, and compliant regulatory policies with LATRA, MoT, and local authorities.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-emerald-700">
                    <span>Apex Voice</span>
                    <span class="text-slate-400 font-normal">All 31 Regions</span>
                </div>
            </div>

            <!-- Pillar 2 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-cyan-500/60 transition bg-white flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-700 flex items-center justify-center font-black mb-4 border border-cyan-200/80 shadow-xs group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-cyan-700 bg-cyan-50 px-2.5 py-0.5 rounded-md inline-block mb-2">Upskilling</span>
                    <h3 class="font-black text-base text-slate-900 font-heading mb-2">High-Voltage Academies</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Accredited technical curricula in defensive driving, battery swap protocols, thermal runaway safety, and digital passenger customer care.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-cyan-700">
                    <span>Certified Tracks</span>
                    <span class="text-slate-400 font-normal">QR-Verifiable</span>
                </div>
            </div>

            <!-- Pillar 3 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-indigo-500/60 transition bg-white flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-black mb-4 border border-indigo-200/80 shadow-xs group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-md inline-block mb-2">Economic Empowerment</span>
                    <h3 class="font-black text-base text-slate-900 font-heading mb-2">Green Finance & Grants</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Connecting verified members to affordable lease-to-own EV models, low-interest green loans, equipment grants, and battery financing.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-indigo-700">
                    <span>Asset Access</span>
                    <span class="text-slate-400 font-normal">Bajaji, Bikes & Cars</span>
                </div>
            </div>

            <!-- Pillar 4 -->
            <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs hover:border-amber-500/60 transition bg-white flex flex-col justify-between group">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center font-black mb-4 border border-amber-200/80 shadow-xs group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-amber-700 bg-amber-50 px-2.5 py-0.5 rounded-md inline-block mb-2">Sustainability</span>
                    <h3 class="font-black text-base text-slate-900 font-heading mb-2">Clean Urban Mobility</h3>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        Accelerating zero tailpipe emissions across urban transport corridors while fostering youth and women-led electric mobility entrepreneurship.
                    </p>
                </div>
                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between text-[11px] font-bold text-amber-700">
                    <span>Zero Emissions</span>
                    <span class="text-slate-400 font-normal">Climate Action</span>
                </div>
            </div>
        </div>

        <!-- SMART Principles Showcase Container -->
        <div class="mb-20">
            <!-- Header for SMART Principles -->
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200/60 inline-block mb-2">
                    {{ \App\Models\Setting::get('smart_principles_badge', 'Operational Philosophy') }}
                </span>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900 font-heading">
                    {{ \App\Models\Setting::get('smart_principles_title', 'Our SMART Principles') }}
                </h3>
                <p class="text-xs sm:text-sm text-slate-500 mt-2 font-medium">
                    {{ \App\Models\Setting::get('smart_principles_subtitle', 'The foundational pillars guiding every TEVDA driver, operator, and operational standard') }}
                </p>
            </div>

            <!-- SMART Principles 5 Cards Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                
                <!-- S: Safety -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-emerald-500/60 bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-emerald-600/20 group-hover:scale-110 transition-transform">
                                S
                            </div>
                            <span class="text-[10px] font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60">Usalama</span>
                        </div>
                        <h4 class="font-black text-lg text-slate-900 mb-2 font-heading">
                            {{ \App\Models\Setting::get('smart_s_title', 'Safety') }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ \App\Models\Setting::get('smart_s_desc', 'Zero preventable accidents through rigorous defensive driving, high-voltage battery operating standards, and pedestrian awareness in silent electric vehicles.') }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold text-emerald-700">
                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>Defensive HV Roadcraft</span>
                    </div>
                </div>

                <!-- M: Modern Tech -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-cyan-500/60 bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-700 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-cyan-600/20 group-hover:scale-110 transition-transform">
                                M
                            </div>
                            <span class="text-[10px] font-extrabold text-cyan-700 bg-cyan-50 px-2 py-0.5 rounded-full border border-cyan-200/60">Teknolojia</span>
                        </div>
                        <h4 class="font-black text-lg text-slate-900 mb-2 font-heading">
                            {{ \App\Models\Setting::get('smart_m_title', 'Modern Tech') }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ \App\Models\Setting::get('smart_m_desc', 'Embracing smart EV powertrains, telemetry, rapid battery swapping networks, smart charging infrastructure, and digital booking tools.') }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold text-cyan-700">
                        <svg class="w-3.5 h-3.5 text-cyan-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Battery Swapping & IoT</span>
                    </div>
                </div>

                <!-- A: Accountability -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-indigo-500/60 bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-indigo-600/20 group-hover:scale-110 transition-transform">
                                A
                            </div>
                            <span class="text-[10px] font-extrabold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full border border-indigo-200/60">Uwajibikaji</span>
                        </div>
                        <h4 class="font-black text-lg text-slate-900 mb-2 font-heading">
                            {{ \App\Models\Setting::get('smart_a_title', 'Accountability') }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ \App\Models\Setting::get('smart_a_desc', 'Financial transparency, strict regulatory compliance with transport authorities (LATRA), and ethical member representation.') }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold text-indigo-700">
                        <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>100% LATRA Compliant</span>
                    </div>
                </div>

                <!-- R: Respect -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-amber-500/60 bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-500 to-amber-700 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-amber-600/20 group-hover:scale-110 transition-transform">
                                R
                            </div>
                            <span class="text-[10px] font-extrabold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full border border-amber-200/60">Heshima</span>
                        </div>
                        <h4 class="font-black text-lg text-slate-900 mb-2 font-heading">
                            {{ \App\Models\Setting::get('smart_r_title', 'Respect') }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ \App\Models\Setting::get('smart_r_desc', 'Passenger dignity, mutual driver solidarity, ethical business conduct, gender inclusivity, and professional road etiquette.') }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold text-amber-700">
                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span>Dignity & Gender Equity</span>
                    </div>
                </div>

                <!-- T: Teamwork -->
                <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group hover:border-teal-500/60 bg-white">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-700 text-white font-black text-2xl flex items-center justify-center shadow-md shadow-teal-600/20 group-hover:scale-110 transition-transform">
                                T
                            </div>
                            <span class="text-[10px] font-extrabold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-full border border-teal-200/60">Umoja</span>
                        </div>
                        <h4 class="font-black text-lg text-slate-900 mb-2 font-heading">
                            {{ \App\Models\Setting::get('smart_t_title', 'Teamwork') }}
                        </h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4">
                            {{ \App\Models\Setting::get('smart_t_desc', 'Uniting drivers, technicians, energy companies, financiers, and government bodies to build a thriving clean mobility economy.') }}
                        </p>
                    </div>
                    <div class="pt-3 border-t border-slate-100 flex items-center gap-1.5 text-[11px] font-bold text-teal-700">
                        <svg class="w-3.5 h-3.5 text-teal-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Multi-Sector Ecosystem</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Institutional Trust & Action Strip -->
        <div class="mb-16 p-6 sm:p-8 bg-slate-50 rounded-3xl border border-slate-200/80 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-center gap-4 text-left">
                <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-600/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h4 class="font-black text-slate-900 font-heading text-sm sm:text-base">Explore TEVDA’s Full Institutional Charter & Governance</h4>
                    <p class="text-xs text-slate-600 mt-0.5">Learn about our founding story, executive council, constitution, and strategic roadmaps.</p>
                </div>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <a href="{{ route('about') }}" class="px-5 py-2.5 bg-white hover:bg-slate-100 text-slate-900 font-bold text-xs sm:text-sm rounded-xl border border-slate-200 transition shadow-xs">
                    About Association &rarr;
                </a>
                <a href="{{ route('leadership') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs sm:text-sm rounded-xl transition shadow-md shadow-emerald-600/20">
                    Governance Council &rarr;
                </a>
            </div>
        </div>

        <!-- Membership Categories Overview -->
        <div class="bg-slate-950 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden border border-slate-800">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="max-w-3xl mb-10 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3.5 py-1.5 rounded-xl border border-emerald-800">Membership Tiers</span>
                <h3 class="text-2xl sm:text-3xl font-black font-heading mt-3 mb-2 text-white">Join Tanzania's EV Driver Community</h3>
                <p class="text-slate-300 text-xs sm:text-sm">Select the category that matches your role in the clean transportation ecosystem.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 relative z-10">
                @foreach ($categories as $cat)
                    <div class="bg-slate-900/90 border border-slate-800 hover:border-emerald-500/60 p-6 rounded-2xl flex flex-col justify-between transition-all duration-300 hover:shadow-xl hover:shadow-emerald-950/40">
                        <div>
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-black text-base text-white font-heading">{{ $cat->name }}</h4>
                                <span class="text-[10px] bg-emerald-500/20 text-emerald-300 px-2.5 py-0.5 rounded-full font-bold border border-emerald-500/30">Tier {{ $cat->order_number }}</span>
                            </div>
                            <p class="text-xs text-slate-300 leading-relaxed mb-4 line-clamp-3">{{ $cat->description }}</p>
                        </div>
                        <a href="{{ route('register') }}" class="mt-4 w-full text-center bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold py-2.5 rounded-xl transition shadow-md">
                            Apply for {{ $cat->name }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

<!-- Featured Programmes & EV Projects -->
<section class="py-20 bg-slate-50 border-t border-slate-200/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-4">
            <div>
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-100 px-3.5 py-1.5 rounded-xl">Capacity Building</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-heading mt-3">Driver Training Programmes</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Specialized curricula for high-voltage efficiency, defensive driving, and small-business management.</p>
            </div>
            <a href="{{ route('programmes') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-800">
                <span>View All Programmes</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        <!-- Programmes Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
            @foreach ($programmes as $prog)
                <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                    <div>
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-black text-xs mb-4 border border-emerald-200/60">
                            {{ $prog->code }}
                        </div>
                        <h3 class="font-black text-base text-slate-900 mb-2 font-heading">{{ $prog->title }}</h3>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">{{ $prog->description }}</p>
                    </div>
                    <a href="{{ route('programmes') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 mt-2">
                        <span>Course Modules</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Flagship EV Project Showcase -->
        <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 rounded-3xl p-8 sm:p-12 text-white shadow-2xl relative overflow-hidden border border-emerald-500/20">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-4">
                    <div class="inline-flex items-center gap-2 bg-amber-500/20 border border-amber-400/40 px-3 py-1 rounded-full text-amber-300 text-xs font-extrabold uppercase">
                        Flagship Project Showcase
                    </div>
                    <h3 class="text-2xl sm:text-3xl font-black text-white font-heading">
                        50 Electric Three-Wheeler (Bajaj) Deployment Programme
                    </h3>
                    <p class="text-slate-300 text-xs sm:text-sm leading-relaxed">
                        A structured initiative targeting commercial tricycle operators in Dar es Salaam & Coastal Region with electric vehicle lease-to-own models, battery swapping access, and certified training.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 pt-2">
                        <span class="inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                            Status: Funding Proposal Under Development
                        </span>
                        <span>•</span>
                        <span>Target: 50 Commercial Bajaj Drivers</span>
                    </div>
                </div>
                <div class="lg:col-span-4 flex justify-start lg:justify-end">
                    <a href="{{ route('projects.show', '50-electric-three-wheeler-programme') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-7 py-4 rounded-2xl text-xs sm:text-sm transition shadow-xl hover:shadow-emerald-500/30">
                        View Project Details
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Latest Opportunities & News -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <!-- Opportunities Col -->
            <div class="lg:col-span-7">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-700 bg-indigo-50 px-3 py-1 rounded-xl">Verified Openings</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading mt-2">Latest Opportunities</h2>
                    </div>
                    <a href="{{ route('opportunities') }}" class="text-xs font-bold text-indigo-700 hover:text-indigo-800">View All &rarr;</a>
                </div>

                <div class="space-y-4">
                    @forelse ($opportunities as $opp)
                        <div class="glass-card p-5 rounded-2xl border border-slate-200/80 hover:border-indigo-500 transition shadow-xs flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] uppercase font-extrabold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $opp->category->name }}</span>
                                    @if ($opp->deadline)
                                        <span class="text-[10px] text-slate-500">Deadline: {{ $opp->deadline->format('d M Y') }}</span>
                                    @endif
                                </div>
                                <h3 class="font-black text-base text-slate-900 font-heading">
                                    <a href="{{ route('opportunities.show', $opp->slug) }}" class="hover:text-indigo-600 transition">{{ $opp->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-500">{{ $opp->provider_name }} • {{ $opp->location }}</p>
                            </div>
                            <a href="{{ route('opportunities.show', $opp->slug) }}" class="inline-flex items-center justify-center bg-slate-950 hover:bg-indigo-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl whitespace-nowrap transition">
                                Details
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-xs">
                            New opportunities are actively vetted and published by the TEVDA Opportunities Directorate.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- News & Announcements Col -->
            <div class="lg:col-span-5">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 rounded-xl">Secretariat Updates</span>
                        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading mt-2">Latest News</h2>
                    </div>
                    <a href="{{ route('news') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">View All &rarr;</a>
                </div>

                <div class="space-y-4">
                    @forelse ($news as $item)
                        <div class="glass-card p-5 rounded-2xl border border-slate-200/80 hover:border-emerald-500 transition shadow-xs">
                            <div class="flex items-center justify-between gap-2 mb-1.5">
                                <span class="text-[10px] uppercase font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md">{{ ucwords(str_replace('_', ' ', $item->category)) }}</span>
                                <span class="text-[10px] text-slate-400">{{ $item->publication_date->format('d M Y') }}</span>
                            </div>
                            <h3 class="font-black text-base text-slate-900 font-heading">
                                <a href="{{ route('news.show', $item->slug) }}" class="hover:text-emerald-700 transition">{{ $item->title }}</a>
                            </h3>
                            <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $item->summary }}</p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 bg-slate-50 rounded-2xl border border-dashed border-slate-300 text-xs">
                            Official media releases and announcements from TEVDA Secretariat.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Confirmed Partners Section with Real Logo Support -->
<section class="py-16 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-extrabold uppercase tracking-widest text-slate-500 bg-slate-200/80 px-3.5 py-1 rounded-full">Collaborative Ecosystem</span>
        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading mt-2 mb-8">Strategic Stakeholders & Partners</h2>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
            @forelse ($partners as $partner)
                <div class="glass-card p-6 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col items-center justify-center text-center group">
                    <div class="w-16 h-16 rounded-2xl bg-white flex items-center justify-center p-2 mb-3 shadow-xs border border-slate-100 overflow-hidden">
                        @if ($partner->logo_url)
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-12 max-w-full object-contain group-hover:scale-105 transition-transform" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                            <div class="hidden w-full h-full rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-700 text-sm">
                                {{ strtoupper(substr($partner->name, 0, 3)) }}
                            </div>
                        @else
                            <div class="w-full h-full rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-700 text-sm">
                                {{ strtoupper(substr($partner->name, 0, 3)) }}
                            </div>
                        @endif
                    </div>
                    <h3 class="font-black text-xs text-slate-900 font-heading">{{ $partner->name }}</h3>
                    <p class="text-[10px] text-slate-500 mt-0.5">{{ ucwords(str_replace('_', ' ', $partner->category)) }}</p>
                </div>
            @empty
                <div class="col-span-4 py-8 text-xs text-slate-400">
                    Strategic partner directory under active compilation.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            <a href="{{ route('partners') }}" class="inline-flex items-center gap-2 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-800">
                <span>Explore Partner Network & Inquire for Strategic Collaboration</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
<section class="py-16 bg-slate-950 text-white relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6 relative z-10">
        <h2 class="text-3xl sm:text-4xl font-black font-heading text-white">Ready to Accelerate Your EV Career or Fleet?</h2>
        <p class="text-slate-300 text-xs sm:text-base max-w-2xl mx-auto leading-relaxed">
            Join the official association representing Tanzania's electric vehicle drivers, operators, and technicians. Access certified skills, green financing, and digital transport tools.
        </p>
        <div class="flex flex-wrap justify-center gap-4 pt-2">
            <a href="{{ route('register') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-8 py-4 rounded-2xl shadow-xl hover:shadow-emerald-500/30 transition transform hover:-translate-y-0.5 text-xs sm:text-sm">
                Register for Membership
            </a>
            <a href="{{ route('contact') }}" class="bg-slate-900 hover:bg-slate-800 text-white font-bold px-8 py-4 rounded-2xl border border-slate-700 transition text-xs sm:text-sm">
                Contact Secretariat
            </a>
        </div>
    </div>
</section>
@endsection
