<!-- Topbar -->
<div class="bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 text-slate-300 text-xs py-2 px-4 border-b border-emerald-900/30">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 sm:gap-6">
            <span class="inline-flex items-center gap-1.5 text-emerald-400 font-extrabold tracking-wider text-[11px] sm:text-xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                {{ __('SMART DRIVERS • SMART MOBILITY') }}
            </span>
            <span class="hidden sm:inline-block text-slate-700">|</span>
            <a href="tel:+255757700401" class="hover:text-emerald-400 transition-colors inline-flex items-center gap-1.5 text-slate-300 text-[11px] sm:text-xs">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                +255 757 700 401
            </a>
            <a href="mailto:info@tevda.or.tz" class="hover:text-emerald-400 transition-colors inline-flex items-center gap-1.5 text-slate-300 text-[11px] sm:text-xs hidden md:inline-flex">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                info@tevda.or.tz
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden lg:inline-flex items-center gap-1 text-slate-400 text-[11px]">
                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ __('Sinza Mori, Dar es Salaam') }}
            </span>
            <div class="flex items-center gap-2">
                <a href="{{ route('track.application') }}" class="text-[11px] bg-amber-500 text-slate-950 hover:bg-amber-400 font-extrabold px-2.5 py-1 rounded-lg transition-all flex items-center gap-1 shadow-xs">
                    <svg class="w-3 h-3 text-slate-950" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>{{ __('Track Application') }}</span>
                </a>
                <a href="{{ route('verify.membership') }}" class="text-[11px] bg-emerald-950/90 text-emerald-300 hover:bg-emerald-900 border border-emerald-700/60 px-2.5 py-1 rounded-lg transition-all font-semibold flex items-center gap-1 shadow-xs hover:border-emerald-500">
                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Verify Member') }}</span>
                </a>
                <a href="{{ route('verify.certificate') }}" class="text-[11px] bg-slate-900/90 text-slate-300 hover:bg-slate-800 border border-slate-700 px-2.5 py-1 rounded-lg transition font-medium flex items-center gap-1 hover:text-white">
                    <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>{{ __('Verify Certificate') }}</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation Header -->
<header class="bg-white/95 backdrop-blur-lg border-b border-slate-200/80 sticky top-0 z-40 shadow-xs" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                @if(\App\Models\Setting::hasCustomLogo())
                    <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-12 w-auto max-w-[170px] object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                    <div class="hidden w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                        <span class="tracking-tighter">TEV</span>
                    </div>
                @else
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 flex items-center justify-center text-white font-black text-xl shadow-md shadow-emerald-700/20 group-hover:scale-105 transition-transform">
                        <span class="tracking-tighter">TEV</span>
                    </div>
                @endif
                <div>
                    <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 block font-heading leading-none group-hover:text-emerald-700 transition-colors">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }}</span>
                    <span class="text-[9px] sm:text-[10px] uppercase font-extrabold tracking-wider text-emerald-700 block mt-0.5">{{ __('Tanzania Electric Vehicle Drivers Association') }}</span>
                </div>
            </a>

            <!-- Desktop Menu (Grouped & Structured) -->
            <nav class="hidden lg:flex items-center space-x-1 xl:space-x-1.5">
                <!-- Home -->
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50/90 shadow-xs font-extrabold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }}">
                    {{ __('Home') }}
                </a>
                
                <!-- 1. About Us Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all duration-200 {{ request()->routeIs('about*') || request()->routeIs('leadership*') || request()->routeIs('membership.info') || request()->routeIs('partners*') ? 'text-emerald-700 bg-emerald-50/90 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }}">
                        <span>{{ __('About') }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute left-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 mt-1 z-50 ring-1 ring-black/5"
                         style="display: none;">
                        <div class="px-3 py-1.5 mb-1 border-b border-slate-100">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">{{ __('About TEVDA') }}</span>
                        </div>
                        <a href="{{ route('about') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-emerald-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-800">{{ __('About TEVDA & SMART') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Our vision, core mission & mandate') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('leadership') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-cyan-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 group-hover:bg-cyan-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-cyan-800">{{ __('Governance & Leadership') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Board of Trustees & Secretariat') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('membership.info') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-amber-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-amber-800">{{ __('Membership Categories') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Driver tiers, benefits & requirements') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('partners') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-indigo-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-indigo-800">{{ __('Strategic Partners') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Government, financiers & EV allies') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 2. Initiatives & Programmes Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all duration-200 {{ request()->routeIs('programmes*') || request()->routeIs('projects*') || request()->routeIs('opportunities*') ? 'text-emerald-700 bg-emerald-50/90 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }}">
                        <span>{{ __('Programmes & Projects') }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute left-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 mt-1 z-50 ring-1 ring-black/5"
                         style="display: none;">
                        <div class="px-3 py-1.5 mb-1 border-b border-slate-100">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">{{ __('Driver Initiatives') }}</span>
                        </div>
                        <a href="{{ route('programmes') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-emerald-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-800">{{ __('Driver Programmes') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('EV skills training & safety certification') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('projects') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-teal-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center shrink-0 group-hover:bg-teal-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-teal-800">{{ __('EV Projects Showcase') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Fleet electrification & charging hubs') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('opportunities') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-amber-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-amber-800">{{ __('Opportunities Directory') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Driver jobs, fleet tenders & grants') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 3. Media & Resources Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all duration-200 {{ request()->routeIs('news*') || request()->routeIs('resources*') || request()->routeIs('whistleblower*') ? 'text-emerald-700 bg-emerald-50/90 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }}">
                        <span>{{ __('Media & Hub') }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute left-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 mt-1 z-50 ring-1 ring-black/5"
                         style="display: none;">
                        <div class="px-3 py-1.5 mb-1 border-b border-slate-100">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">{{ __('Knowledge & News') }}</span>
                        </div>
                        <a href="{{ route('news') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-sky-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-sky-100 text-sky-700 flex items-center justify-center shrink-0 group-hover:bg-sky-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-sky-800">{{ __('News & Media Releases') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Updates, press releases & events') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('resources') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-emerald-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-800">{{ __('Resource Center') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Policy briefs, guidelines & downloads') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('whistleblower') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-rose-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-rose-100 text-rose-700 flex items-center justify-center shrink-0 group-hover:bg-rose-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-rose-800">{{ __('Whistleblower Portal') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Confidential reporting & transparency') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 4. Verification Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 transition-all duration-200 {{ request()->routeIs('verify*') || request()->routeIs('track*') ? 'text-emerald-700 bg-emerald-50/90 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }}">
                        <span>{{ __('Verification') }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute left-0 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 p-2 mt-1 z-50 ring-1 ring-black/5"
                         style="display: none;">
                        <div class="px-3 py-1.5 mb-1 border-b border-slate-100">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">{{ __('Public Verification & Tracking') }}</span>
                        </div>
                        <a href="{{ route('track.application') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-amber-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 group-hover:bg-amber-500 group-hover:text-slate-950 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-amber-900">{{ __('Track Application Status') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Check Control No & registration progress') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('verify.membership') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-emerald-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-emerald-800">{{ __('Verify Member Status') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Check active registration & ID card') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('verify.certificate') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-cyan-50/80 group transition">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 group-hover:bg-cyan-600 group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-800 group-hover:text-cyan-800">{{ __('Verify Certificate') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Authenticate official certifications') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 5. Contact -->
                <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('contact') ? 'text-emerald-700 bg-emerald-50/90 shadow-xs font-extrabold' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }}">
                    {{ __('Contact') }}
                </a>
            </nav>

            <!-- Actions (Right CTAs + Language Selector) -->
            <div class="hidden md:flex items-center space-x-2.5">
                <!-- Language Selector Dropdown -->
                @include('partials.language_selector')

                @auth
                    @if (auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-950 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition transform hover:-translate-y-0.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                            <span>{{ __('Admin Suite') }}</span>
                        </a>
                    @else
                        <a href="{{ route('portal.dashboard') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-emerald-600/20 transition transform hover:-translate-y-0.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ __('Member Portal') }}</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-slate-500 hover:text-rose-600 px-2.5 py-1.5 rounded-lg hover:bg-rose-50 font-medium transition" title="{{ __('Log Out') }}">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-emerald-700 hover:bg-slate-50 text-xs font-bold px-3.5 py-2.5 rounded-xl transition">
                        {{ __('Log In') }}
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl shadow-md shadow-emerald-600/25 transition-all duration-200 transform hover:-translate-y-0.5 hover:shadow-lg hover:shadow-emerald-600/35">
                        <span>{{ __('Join TEVDA') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="lg:hidden flex items-center gap-2">
                <!-- Compact Language Selector for Mobile Header Top -->
                @include('partials.language_selector')

                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-700 hover:text-emerald-600 p-2.5 rounded-xl hover:bg-slate-100 transition focus:outline-hidden" aria-label="Toggle menu">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu Drawer -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-250" 
         x-transition:enter-start="opacity-0 -translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-4" 
         class="lg:hidden border-t border-slate-100 bg-white/98 backdrop-blur-xl px-4 pt-3 pb-8 shadow-2xl max-h-[calc(100vh-5rem)] overflow-y-auto"
         style="display: none;">
        
        <div class="space-y-4">
            <!-- Language Selector for Mobile Drawer -->
            @include('partials.language_selector_mobile')

            <!-- Home -->
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 px-3.5 py-2.5 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800' : 'text-slate-800 hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('Home') }}</span>
                </a>
            </div>

            <!-- About Section -->
            <div class="border-t border-slate-100 pt-3">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3.5 mb-1.5">{{ __('About TEVDA') }}</div>
                <div class="space-y-1">
                    <a href="{{ route('about') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ __('About TEVDA & SMART') }}</span>
                    </a>
                    <a href="{{ route('leadership') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                        <span>{{ __('Governance & Leadership') }}</span>
                    </a>
                    <a href="{{ route('membership.info') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>{{ __('Membership Categories') }}</span>
                    </a>
                    <a href="{{ route('partners') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-500"></span>
                        <span>{{ __('Strategic Partners') }}</span>
                    </a>
                </div>
            </div>

            <!-- Programmes & Projects -->
            <div class="border-t border-slate-100 pt-3">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3.5 mb-1.5">{{ __('Programmes & Projects') }}</div>
                <div class="space-y-1">
                    <a href="{{ route('programmes') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ __('Driver Training Programmes') }}</span>
                    </a>
                    <a href="{{ route('projects') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                        <span>{{ __('EV Projects Showcase') }}</span>
                    </a>
                    <a href="{{ route('opportunities') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        <span>{{ __('Opportunities Directory') }}</span>
                    </a>
                </div>
            </div>

            <!-- Media & Resources -->
            <div class="border-t border-slate-100 pt-3">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3.5 mb-1.5">{{ __('Media & Resources') }}</div>
                <div class="space-y-1">
                    <a href="{{ route('news') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                        <span>{{ __('News & Media Releases') }}</span>
                    </a>
                    <a href="{{ route('resources') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>{{ __('Resource Center & Downloads') }}</span>
                    </a>
                    <a href="{{ route('whistleblower') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-bold text-rose-700 bg-rose-50/60 border border-rose-100">
                        <svg class="w-3.5 h-3.5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>{{ __('Whistleblower Portal') }}</span>
                    </a>
                </div>
            </div>

            <!-- Verification & Contact -->
            <div class="border-t border-slate-100 pt-3">
                <div class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 px-3.5 mb-1.5">{{ __('Quick Tracking & Verification') }}</div>
                <div class="grid grid-cols-3 gap-2 px-1 mb-2">
                    <a href="{{ route('track.application') }}" class="flex flex-col items-center justify-center gap-1 p-2 rounded-xl bg-amber-50 text-amber-950 text-[11px] font-bold border border-amber-200 text-center">
                        <svg class="w-4 h-4 text-amber-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span>{{ __('Track Status') }}</span>
                    </a>
                    <a href="{{ route('verify.membership') }}" class="flex flex-col items-center justify-center gap-1 p-2 rounded-xl bg-emerald-50 text-emerald-800 text-[11px] font-bold border border-emerald-100 text-center">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ __('Member') }}</span>
                    </a>
                    <a href="{{ route('verify.certificate') }}" class="flex flex-col items-center justify-center gap-1 p-2 rounded-xl bg-slate-100 text-slate-800 text-[11px] font-bold border border-slate-200 text-center">
                        <svg class="w-4 h-4 text-cyan-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ __('Certificate') }}</span>
                    </a>
                </div>
                <a href="{{ route('contact') }}" class="flex items-center gap-2.5 px-3.5 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-slate-50">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>{{ __('Contact Secretariat') }}</span>
                </a>
            </div>

            <!-- Mobile Authentication Actions -->
            <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    <a href="{{ auth()->user()->isStaff() ? route('admin.dashboard') : route('portal.dashboard') }}" class="w-full text-center bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-md">
                        {{ __('Go to') }} {{ auth()->user()->isStaff() ? __('Admin Suite') : __('Member Portal') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-center py-2 text-xs text-slate-500 hover:text-rose-600 font-medium">{{ __('Log Out') }}</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="w-full text-center bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-extrabold py-3 rounded-xl shadow-md text-sm">{{ __('Join TEVDA Today') }}</a>
                    <a href="{{ route('login') }}" class="w-full text-center border border-slate-200 text-slate-700 font-bold py-2.5 rounded-xl text-xs hover:bg-slate-50">{{ __('Log In to Portal') }}</a>
                @endauth
            </div>
        </div>
    </div>
</header>
