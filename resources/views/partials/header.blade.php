<!-- Top Information Bar -->
<div class="bg-[#002040] text-slate-200 text-xs py-2 px-3 sm:px-4 border-b border-[#002c59]">
    <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row justify-between items-center gap-2 sm:gap-3 px-1 sm:px-4 lg:px-6">
        
        <!-- Left: Slogan & Contact Information -->
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5 sm:gap-4">
            <span class="inline-flex items-center gap-1.5 sm:gap-2 text-[#E5B51B] font-extrabold tracking-wider text-[10px] xs:text-[11px] sm:text-xs">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#108010] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#108010]"></span>
                </span>
                {{ __('SMART DRIVERS • SMART MOBILITY') }}
            </span>
            
            <span class="hidden md:inline-block text-slate-600">|</span>
            
            <a href="tel:+255757700401" class="hover:text-[#E5B51B] transition-colors inline-flex items-center gap-1.5 text-slate-300 text-[10px] xs:text-[11px] sm:text-xs">
                <svg class="w-3.5 h-3.5 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>+255 757 700 401</span>
            </a>
            
            <a href="mailto:info@tevda.or.tz" class="hover:text-[#E5B51B] transition-colors inline-flex items-center gap-1.5 text-slate-300 text-[11px] sm:text-xs hidden lg:inline-flex">
                <svg class="w-3.5 h-3.5 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                <span>info@tevda.or.tz</span>
            </a>
        </div>

        <!-- Right: Location & Quick Access Verification Actions -->
        <div class="flex flex-wrap items-center justify-center gap-1.5 sm:gap-3">
            <span class="hidden 2xl:inline-flex items-center gap-1.5 text-slate-400 text-[11px]">
                <svg class="w-3.5 h-3.5 text-[#E5B51B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>{{ __('Sinza Mori, Dar es Salaam') }}</span>
            </span>

            <div class="flex items-center gap-1.5 sm:gap-2">
                <a href="{{ route('track.application') }}" class="text-[10px] sm:text-[11px] bg-[#E5B51B] text-[#002040] hover:bg-[#c79b0e] font-extrabold px-2.5 sm:px-3 py-1 rounded-md transition-all flex items-center gap-1 shadow-xs whitespace-nowrap">
                    <svg class="w-3 h-3 text-[#002040] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    <span>{{ __('Track') }}</span>
                    <span class="hidden xs:inline">{{ __('Application') }}</span>
                </a>
                
                <a href="{{ route('verify.membership') }}" class="text-[10px] sm:text-[11px] bg-[#001428] text-emerald-300 hover:text-white border border-[#108010]/70 hover:border-[#108010] px-2.5 sm:px-3 py-1 rounded-md transition-all font-semibold flex items-center gap-1 shadow-xs whitespace-nowrap">
                    <svg class="w-3 h-3 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ __('Verify Member') }}</span>
                </a>

                <a href="{{ route('verify.certificate') }}" class="text-[10px] sm:text-[11px] bg-[#002c59] text-slate-200 hover:text-white border border-slate-600 px-2.5 sm:px-3 py-1 rounded-md transition font-medium flex items-center gap-1 whitespace-nowrap">
                    <svg class="w-3 h-3 text-[#E5B51B] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span class="hidden sm:inline">{{ __('Verify Certificate') }}</span>
                    <span class="sm:hidden">{{ __('Certificate') }}</span>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Main Navigation Header -->
<header class="bg-white sticky top-0 z-40 border-b border-stone-200 shadow-xs" 
        x-data="{ mobileMenuOpen: false }"
        x-effect="if (mobileMenuOpen) { document.documentElement.style.overflow = 'hidden'; document.body.style.overflow = 'hidden'; } else { document.documentElement.style.overflow = ''; document.body.style.overflow = ''; }">
    <div class="max-w-[1440px] mx-auto px-3 sm:px-6 lg:px-8 xl:px-10">
        <div class="flex items-center justify-between h-18 sm:h-20 gap-2 sm:gap-4">
            
            <!-- 1. TEVDA Logo & Organization Name (Far Left) -->
            <div class="flex items-center shrink-0 min-w-0">
                <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group min-w-0">
                    @if(\App\Models\Setting::hasCustomLogo())
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-9 sm:h-12 w-auto max-w-[130px] sm:max-w-[160px] object-contain group-hover:scale-102 transition-transform duration-300 shrink-0" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        <div class="hidden w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-[#002040] border-2 border-[#108010] flex items-center justify-center text-white font-black text-sm sm:text-lg shadow-md group-hover:scale-102 transition-transform shrink-0">
                            <span class="tracking-tighter text-[#E5B51B]">TEV</span>
                        </div>
                    @else
                        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-[#002040] border-2 border-[#108010] flex items-center justify-center text-white font-black text-sm sm:text-lg shadow-md group-hover:scale-102 transition-transform shrink-0">
                            <span class="tracking-tighter text-[#E5B51B]">TEV</span>
                        </div>
                    @endif
                    <div class="flex flex-col justify-center min-w-0">
                        <span class="text-lg sm:text-2xl font-black tracking-tight text-[#002040] font-heading leading-tight group-hover:text-[#108010] transition-colors truncate">
                            {{ \App\Models\Setting::get('site_short_name', 'TEVDA') }}
                        </span>
                        <span class="text-[8px] sm:text-[10px] font-extrabold uppercase tracking-wider text-[#108010] leading-none block mt-0.5 truncate max-w-[180px] xs:max-w-[240px] sm:max-w-none">
                            {{ __('Tanzania Electric Vehicle Drivers Association') }}
                        </span>
                    </div>
                </a>
            </div>

            <!-- 2-9. Center Navigation Links (Cleanly Balanced & Centered) -->
            <nav class="hidden lg:flex items-center justify-center space-x-1 xl:space-x-1.5 2xl:space-x-2.5">
                
                <!-- 2. Home -->
                <a href="{{ route('home') }}" class="px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13px] font-bold transition-all duration-200 {{ request()->routeIs('home') ? 'text-[#108010] bg-[#f2fbf2] font-black border border-[#108010]/30 shadow-2xs' : 'text-[#002040] hover:text-[#108010] hover:bg-slate-50' }}">
                    {{ __('Home') }}
                </a>
                
                <!-- 3. About (with Dropdown) -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button type="button" class="px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13px] font-bold flex items-center gap-1.5 transition-all duration-200 cursor-pointer {{ request()->routeIs('about*') || request()->routeIs('leadership*') || request()->routeIs('membership.info') || request()->routeIs('partners*') ? 'text-[#108010] bg-[#f2fbf2] font-black border border-[#108010]/30 shadow-2xs' : 'text-[#002040] hover:text-[#108010] hover:bg-slate-50' }}">
                        <span>{{ __('About') }}</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-[#108010]': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200" 
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95" 
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave="transition ease-in duration-150" 
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95" 
                         class="absolute left-0 w-80 bg-white rounded-2xl shadow-xl border border-stone-200 p-2 mt-1 z-50 ring-1 ring-black/5"
                         style="display: none;">
                        <div class="px-3 py-1.5 mb-1 border-b border-stone-100">
                            <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">{{ __('About TEVDA') }}</span>
                        </div>
                        <a href="{{ route('about') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#f2fbf2] group transition">
                            <div class="w-8 h-8 rounded-lg bg-[#dcfce7] text-[#108010] flex items-center justify-center shrink-0 group-hover:bg-[#108010] group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-[#002040] group-hover:text-[#108010]">{{ __('About TEVDA & SMART') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Our vision, core mission & mandate') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('leadership') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 group transition">
                            <div class="w-8 h-8 rounded-lg bg-[#002040]/10 text-[#002040] flex items-center justify-center shrink-0 group-hover:bg-[#002040] group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-[#002040] group-hover:text-[#002040]">{{ __('Governance & Leadership') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Board of Trustees & Secretariat') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('membership.info') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-[#fefde8] group transition">
                            <div class="w-8 h-8 rounded-lg bg-[#fef9c3] text-[#a17c0a] flex items-center justify-center shrink-0 group-hover:bg-[#E5B51B] group-hover:text-[#002040] transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-[#002040] group-hover:text-[#a17c0a]">{{ __('Membership Categories') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Driver tiers, benefits & requirements') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('partners') }}" class="flex items-start gap-3 p-2.5 rounded-xl hover:bg-slate-50 group transition">
                            <div class="w-8 h-8 rounded-lg bg-slate-100 text-[#002040] flex items-center justify-center shrink-0 group-hover:bg-[#002040] group-hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-[#002040] group-hover:text-[#002040]">{{ __('Strategic Partners') }}</div>
                                <div class="text-[11px] text-slate-500 font-normal">{{ __('Government, financiers & EV allies') }}</div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- 4. Training -->
                <a href="{{ route('programmes') }}" class="px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13px] font-bold transition-all duration-200 {{ request()->routeIs('programmes*') ? 'text-[#108010] bg-[#f2fbf2] font-black border border-[#108010]/30 shadow-2xs' : 'text-[#002040] hover:text-[#108010] hover:bg-slate-50' }}">
                    {{ __('Training') }}
                </a>

                <!-- 5. News -->
                <a href="{{ route('news') }}" class="px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13px] font-bold transition-all duration-200 {{ request()->routeIs('news*') ? 'text-[#108010] bg-[#f2fbf2] font-black border border-[#108010]/30 shadow-2xs' : 'text-[#002040] hover:text-[#108010] hover:bg-slate-50' }}">
                    {{ __('News') }}
                </a>

                <!-- 6. Contact -->
                <a href="{{ route('contact') }}" class="px-2.5 xl:px-3 py-1.5 rounded-lg text-xs xl:text-[13px] font-bold transition-all duration-200 {{ request()->routeIs('contact') ? 'text-[#108010] bg-[#f2fbf2] font-black border border-[#108010]/30 shadow-2xs' : 'text-[#002040] hover:text-[#108010] hover:bg-slate-50' }}">
                    {{ __('Contact') }}
                </a>

            </nav>

            <!-- 10-12. Right Action Buttons (Properly Balanced & Spaced) -->
            <div class="hidden lg:flex items-center space-x-2.5 xl:space-x-3 shrink-0">
                
                <!-- 10. Language Selector -->
                <div class="shrink-0">
                    @include('partials.language_selector')
                </div>

                <div class="h-5 w-px bg-slate-200 shrink-0"></div>

                @auth
                    @if (auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-[#002040] hover:bg-[#002c59] text-white text-xs xl:text-sm font-bold px-4 py-2.5 rounded-xl shadow-xs transition transform hover:-translate-y-0.5 border border-white/10 whitespace-nowrap">
                            <span class="w-2 h-2 rounded-full bg-[#108010]"></span>
                            <span>{{ __('Admin Suite') }}</span>
                        </a>
                    @else
                        <a href="{{ route('portal.dashboard') }}" class="inline-flex items-center gap-2 bg-[#108010] hover:bg-[#0b570b] text-white text-xs xl:text-sm font-bold px-4 py-2.5 rounded-xl shadow-md transition transform hover:-translate-y-0.5 whitespace-nowrap">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span>{{ __('Member Portal') }}</span>
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-slate-500 hover:text-rose-600 px-2.5 py-1.5 rounded-lg hover:bg-rose-50 font-medium transition cursor-pointer" title="{{ __('Log Out') }}">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </form>
                @else
                    <!-- 11. Member Login -->
                    <a href="{{ route('login') }}" class="text-[#002040] hover:text-[#108010] hover:bg-slate-50 text-xs xl:text-sm font-bold px-3.5 xl:px-4 py-2.5 rounded-xl transition-colors border border-slate-200 shadow-2xs whitespace-nowrap">
                        {{ __('Member Login') }}
                    </a>
                    
                    <!-- 12. Become a Member (Primary CTA) -->
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-1.5 bg-[#108010] hover:bg-[#0b570b] text-white text-xs xl:text-sm font-extrabold px-4 xl:px-5 py-2.5 rounded-xl shadow-md transition-all duration-200 transform hover:-translate-y-0.5 whitespace-nowrap">
                        <span>{{ __('Become a Member') }}</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endauth
            </div>

            <!-- Mobile & Tablet Menu Toggle Button (Visible on screens < lg) -->
            <div class="lg:hidden flex items-center gap-1.5 sm:gap-2">
                <div class="shrink-0 scale-95 sm:scale-100">
                    @include('partials.language_selector')
                </div>

                <button type="button" 
                        @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="text-[#002040] hover:text-[#108010] p-2 sm:p-2.5 rounded-xl hover:bg-slate-100 transition focus:outline-hidden cursor-pointer" 
                        aria-label="Toggle menu"
                        :aria-expanded="mobileMenuOpen.toString()">
                    <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Slide-Down & Drawer Navigation (With Background Backdrop) -->
    <div x-show="mobileMenuOpen" 
         class="lg:hidden fixed inset-0 top-[110px] sm:top-[120px] z-50 bg-[#001428]/60 backdrop-blur-xs transition-opacity"
         @click="mobileMenuOpen = false"
         style="display: none;">
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-250" 
         x-transition:enter-start="opacity-0 -translate-y-4" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-200" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-4" 
         class="lg:hidden relative z-50 border-t border-stone-200 bg-white px-4 pt-3 pb-8 shadow-2xl max-h-[calc(100vh-6rem)] overflow-y-auto"
         style="display: none;">
        
        <div class="space-y-4">
            @include('partials.language_selector_mobile')

            <!-- Main Links (In the exact specified order) -->
            <div class="space-y-1">
                <!-- 2. Home -->
                <a href="{{ route('home') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('home') ? 'bg-[#f2fbf2] text-[#108010]' : 'text-[#002040] hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>{{ __('Home') }}</span>
                </a>

                <!-- 3. About Section Submenu -->
                <div x-data="{ aboutOpen: {{ request()->routeIs('about*') || request()->routeIs('leadership*') || request()->routeIs('membership.info') || request()->routeIs('partners*') ? 'true' : 'false' }} }">
                    <button type="button" @click="aboutOpen = !aboutOpen" class="w-full flex items-center justify-between px-3.5 py-3 rounded-xl text-sm font-bold text-[#002040] hover:bg-slate-50 cursor-pointer">
                        <span class="flex items-center gap-2.5">
                            <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ __('About') }}</span>
                        </span>
                        <svg class="w-4 h-4 text-slate-400 transition-transform" :class="{ 'rotate-180': aboutOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="aboutOpen" class="pl-7 pr-2 py-1 space-y-1">
                        <a href="{{ route('about') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-[#108010] hover:bg-[#f2fbf2]">{{ __('About TEVDA & SMART') }}</a>
                        <a href="{{ route('leadership') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-[#108010] hover:bg-[#f2fbf2]">{{ __('Governance & Leadership') }}</a>
                        <a href="{{ route('membership.info') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-[#108010] hover:bg-[#f2fbf2]">{{ __('Membership Categories') }}</a>
                        <a href="{{ route('partners') }}" @click="mobileMenuOpen = false" class="block px-3 py-2 rounded-lg text-xs font-semibold text-slate-600 hover:text-[#108010] hover:bg-[#f2fbf2]">{{ __('Strategic Partners') }}</a>
                    </div>
                </div>

                <!-- 4. Training -->
                <a href="{{ route('programmes') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('programmes*') ? 'bg-[#f2fbf2] text-[#108010]' : 'text-[#002040] hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>{{ __('Training') }}</span>
                </a>

                <!-- 5. News -->
                <a href="{{ route('news') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('news*') ? 'bg-[#f2fbf2] text-[#108010]' : 'text-[#002040] hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>{{ __('News') }}</span>
                </a>

                <!-- 6. Contact -->
                <a href="{{ route('contact') }}" @click="mobileMenuOpen = false" class="flex items-center gap-2.5 px-3.5 py-3 rounded-xl text-sm font-bold {{ request()->routeIs('contact') ? 'bg-[#f2fbf2] text-[#108010]' : 'text-[#002040] hover:bg-slate-50' }}">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>{{ __('Contact') }}</span>
                </a>
            </div>

            <!-- Mobile Authentication Actions (11. Member Login, 12. Become a Member) -->
            <div class="pt-4 border-t border-stone-200 flex flex-col gap-2.5">
                @auth
                    <a href="{{ auth()->user()->isStaff() ? route('admin.dashboard') : route('portal.dashboard') }}" class="w-full text-center bg-[#108010] text-white font-bold py-3 rounded-xl shadow-md min-h-[44px] flex items-center justify-center">
                        {{ __('Go to') }} {{ auth()->user()->isStaff() ? __('Admin Suite') : __('Member Portal') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full text-center py-2.5 text-xs text-slate-500 hover:text-rose-600 font-medium cursor-pointer min-h-[44px] flex items-center justify-center">{{ __('Log Out') }}</button>
                    </form>
                @else
                    <a href="{{ route('register') }}" class="w-full text-center bg-[#108010] hover:bg-[#0b570b] text-white font-extrabold py-3.5 rounded-xl shadow-md text-sm transition min-h-[44px] flex items-center justify-center">
                        {{ __('Become a Member') }}
                    </a>
                    <a href="{{ route('login') }}" class="w-full text-center border border-slate-200 text-[#002040] font-bold py-3 rounded-xl text-xs hover:bg-slate-50 transition min-h-[44px] flex items-center justify-center">
                        {{ __('Member Login') }}
                    </a>
                @endauth
            </div>

        </div>
    </div>
</header>
