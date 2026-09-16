<!-- Topbar -->
<div class="bg-slate-950 text-slate-300 text-xs py-2 px-4 border-b border-slate-800/80">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-2">
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 sm:gap-6">
            <span class="inline-flex items-center gap-1.5 text-emerald-400 font-extrabold tracking-wider text-[11px] sm:text-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                <span class="w-2 h-2 rounded-full bg-emerald-400 -ml-3.5 inline-block"></span>
                SMART DRIVERS SMART MOBILITY
            </span>
            <span class="hidden sm:inline-block text-slate-700">|</span>
            <a href="tel:+255757700401" class="hover:text-emerald-400 transition-colors inline-flex items-center gap-1.5 text-slate-300">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                +255 757 700 401
            </a>
            <a href="mailto:info@tevda.or.tz" class="hover:text-emerald-400 transition-colors inline-flex items-center gap-1.5 text-slate-300 hidden md:inline-flex">
                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                info@tevda.or.tz
            </a>
        </div>
        <div class="flex items-center gap-3">
            <span class="hidden lg:inline text-slate-400 text-[11px]">Sinza Mori, Dar es Salaam</span>
            <div class="flex items-center gap-2">
                <a href="{{ route('verify.membership') }}" class="text-[11px] bg-emerald-950 text-emerald-300 hover:bg-emerald-900 border border-emerald-800/80 px-2.5 py-1 rounded-lg transition font-bold flex items-center gap-1">
                    <svg class="w-3 h-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verify Member
                </a>
                <a href="{{ route('verify.certificate') }}" class="text-[11px] bg-slate-900 text-slate-300 hover:bg-slate-800 border border-slate-700 px-2.5 py-1 rounded-lg transition font-medium">
                    Verify Certificate
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Main Navigation -->
<header class="bg-white/95 backdrop-blur-md border-b border-slate-100 sticky top-0 z-40 shadow-xs" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-3 group shrink-0">
                @if(\App\Models\Setting::hasCustomLogo())
                    <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-12 w-auto max-w-[170px] object-contain group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                    <div class="hidden w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                        <span class="tracking-tighter">TEV</span>
                    </div>
                @else
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 via-teal-700 to-slate-900 flex items-center justify-center text-white font-black text-xl shadow-md group-hover:scale-105 transition-transform">
                        <span class="tracking-tighter">TEV</span>
                    </div>
                @endif
                <div>
                    <span class="text-xl sm:text-2xl font-black tracking-tight text-slate-900 block font-heading leading-none">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }}</span>
                    <span class="text-[9px] sm:text-[10px] uppercase font-extrabold tracking-widest text-emerald-700 block mt-0.5">Tanzania EV Drivers Association</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <nav class="hidden xl:flex items-center space-x-1 lg:space-x-1.5">
                <a href="{{ route('home') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">Home</a>
                
                <!-- About Dropdown -->
                <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                    <button class="px-3 py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 {{ request()->routeIs('about*') || request()->routeIs('leadership*') || request()->routeIs('membership.info') ? 'text-emerald-700 bg-emerald-50' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">
                        <span>About</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180 text-emerald-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2" class="absolute left-0 w-64 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl border border-slate-100 py-2 mt-1 z-50">
                        <a href="{{ route('about') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <div class="w-6 h-6 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span>About TEVDA & SMART</span>
                        </a>
                        <a href="{{ route('leadership') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <div class="w-6 h-6 rounded-lg bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <span>Governance & Leadership</span>
                        </a>
                        <a href="{{ route('membership.info') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition">
                            <div class="w-6 h-6 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            </div>
                            <span>Membership Tiers</span>
                        </a>
                    </div>
                </div>

                <a href="{{ route('programmes') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('programmes*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">Programmes</a>
                <a href="{{ route('projects') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('projects*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">EV Projects</a>
                <a href="{{ route('opportunities') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('opportunities*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">Opportunities</a>
                <a href="{{ route('partners') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('partners*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">Partners</a>
                <a href="{{ route('news') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('news*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">News & Media</a>
                <a href="{{ route('resources') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('resources*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">Resources</a>
                <a href="{{ route('contact') }}" class="px-3 py-2 rounded-xl text-xs font-bold {{ request()->routeIs('contact*') ? 'text-emerald-700 bg-emerald-50 shadow-xs' : 'text-slate-700 hover:text-emerald-700 hover:bg-slate-50' }} transition">Contact</a>
            </nav>

            <!-- Actions -->
            <div class="hidden md:flex items-center space-x-3">
                @auth
                    @if (auth()->user()->isStaff())
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 bg-slate-950 hover:bg-slate-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">
                            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Admin Suite
                        </a>
                    @else
                        <a href="{{ route('portal.dashboard') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Member Portal
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-xs text-slate-500 hover:text-rose-600 px-2 py-1 font-medium transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-700 hover:text-emerald-700 text-xs font-bold px-3 py-2 transition">Log In</a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-extrabold px-5 py-2.5 rounded-xl shadow-md hover:shadow-emerald-600/30 transition transform hover:-translate-y-0.5">
                        <span>Join TEVDA</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="xl:hidden flex items-center">
                <button type="button" @click="mobileMenuOpen = !mobileMenuOpen" class="text-slate-700 hover:text-emerald-600 p-2 rounded-xl focus:outline-hidden">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Dropdown Menu -->
    <div x-show="mobileMenuOpen" x-transition class="xl:hidden border-t border-slate-100 bg-white/98 backdrop-blur-lg px-4 pt-3 pb-6 space-y-1.5 shadow-xl">
        <a href="{{ route('home') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Home</a>
        <a href="{{ route('about') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">About TEVDA</a>
        <a href="{{ route('leadership') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Governance & Leadership</a>
        <a href="{{ route('membership.info') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Membership Categories</a>
        <a href="{{ route('programmes') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Driver Programmes</a>
        <a href="{{ route('projects') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">EV Projects Showcase</a>
        <a href="{{ route('opportunities') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Opportunities Directory</a>
        <a href="{{ route('partners') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Partners & Collaborations</a>
        <a href="{{ route('news') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">News & Media Releases</a>
        <a href="{{ route('resources') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Resource Center</a>
        <a href="{{ route('contact') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-slate-800 hover:bg-emerald-50 hover:text-emerald-700">Contact Secretariat</a>
        <a href="{{ route('whistleblower') }}" class="block px-3 py-2.5 rounded-xl text-sm font-bold text-rose-700 bg-rose-50/80 border border-rose-100">Confidential Whistleblower</a>

        <div class="pt-4 border-t border-slate-100 flex flex-col gap-2">
            @auth
                <a href="{{ auth()->user()->isStaff() ? route('admin.dashboard') : route('portal.dashboard') }}" class="w-full text-center bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-md">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="w-full text-center border border-slate-200 text-slate-700 font-bold py-2.5 rounded-xl">Log In</a>
                <a href="{{ route('register') }}" class="w-full text-center bg-emerald-600 text-white font-bold py-2.5 rounded-xl shadow-md">Join TEVDA</a>
            @endauth
        </div>
    </div>
</header>
