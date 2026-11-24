<footer class="bg-[#002040] text-slate-200 pt-16 pb-12 border-t border-[#002c59] relative overflow-hidden">
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 mb-12">
            <!-- Col 1: Brand & Association Identity -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    @if(\App\Models\Setting::hasCustomLogo())
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-12 w-auto max-w-[170px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        <div class="hidden w-12 h-12 rounded-2xl bg-[#001428] border-2 border-[#108010] flex items-center justify-center text-white font-black text-xl shadow-lg">
                            <span class="tracking-tighter text-[#E5B51B]">TEV</span>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-[#001428] border-2 border-[#108010] flex items-center justify-center text-white font-black text-xl shadow-lg">
                            <span class="tracking-tighter text-[#E5B51B]">TEV</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-xl font-black tracking-tight text-white block font-heading">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }}</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-[#E5B51B] block">{{ __('Tanzania Electric Vehicle Drivers Association') }}</span>
                    </div>
                </div>

                <div class="inline-flex items-center gap-2 bg-[#001428] border border-[#108010]/70 px-3 py-1.5 rounded-xl text-emerald-300 text-xs font-bold tracking-wider shadow-xs">
                    <svg class="w-4 h-4 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <span>{{ __('SMART DRIVERS • SMART MOBILITY') }}</span>
                </div>

                <p class="text-slate-300 text-xs sm:text-sm leading-relaxed pr-6 font-normal">
                    {{ __('Representing, upskilling, and advocating for commercial electric vehicle drivers, battery-swapping operators, technicians, and green mobility innovators across Tanzania.') }}
                </p>

                <div class="pt-2 text-xs text-slate-300 space-y-2">
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ __('Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+255 757 700 401</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@tevda.or.tz</span>
                    </p>
                    <p class="text-[11px] text-slate-400 pt-1">{{ __('Secretariat Office Hours: Monday – Friday, 8:00 a.m. – 5:00 p.m. EAT') }}</p>
                </div>
            </div>

            <!-- Col 2: Association -->
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-widest mb-4 font-heading text-[#108010] flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#108010]"></span>
                    {{ __('Association') }}
                </h4>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-[#E5B51B] transition">{{ __('About TEVDA & SMART') }}</a></li>
                    <li><a href="{{ route('leadership') }}" class="hover:text-[#E5B51B] transition">{{ __('Governance & Leadership') }}</a></li>
                    <li><a href="{{ route('membership.info') }}" class="hover:text-[#E5B51B] transition">{{ __('Membership Categories') }}</a></li>
                    <li><a href="{{ route('partners') }}" class="hover:text-[#E5B51B] transition">{{ __('Strategic Partners') }}</a></li>
                    <li><a href="{{ route('resources') }}" class="hover:text-[#E5B51B] transition">{{ __('Resource Center') }}</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-[#E5B51B] transition">{{ __('News & Media Releases') }}</a></li>
                </ul>
            </div>

            <!-- Col 3: Programmes & EV Projects -->
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-widest mb-4 font-heading text-[#E5B51B] flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-[#E5B51B]"></span>
                    {{ __('Programmes & EV') }}
                </h4>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li><a href="{{ route('programmes') }}" class="hover:text-[#108010] transition">{{ __('Driver Training Programmes') }}</a></li>
                    <li><a href="{{ route('projects') }}" class="hover:text-[#108010] transition">{{ __('EV Projects Showcase') }}</a></li>
                    <li><a href="{{ route('projects.show', '50-electric-three-wheeler-programme') }}" class="hover:text-[#E5B51B] transition text-emerald-400 font-semibold">{{ __('50 Three-Wheeler Programme') }}</a></li>
                    <li><a href="{{ route('opportunities') }}" class="hover:text-[#108010] transition">{{ __('Asset Financing & Grants') }}</a></li>
                    <li><a href="{{ route('opportunities') }}" class="hover:text-[#108010] transition">{{ __('Commercial Fleet Jobs') }}</a></li>
                </ul>
            </div>

            <!-- Col 4: Public Verification & Integrity -->
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-widest mb-4 font-heading text-white flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-white"></span>
                    {{ __('Verification & Trust') }}
                </h4>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li>
                        <a href="{{ route('track.application') }}" class="hover:text-amber-200 transition flex items-center gap-1.5 font-bold text-[#E5B51B]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            {{ __('Track Application') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verify.membership') }}" class="hover:text-emerald-200 transition flex items-center gap-1.5 font-bold text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ __('Verify Member ID') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verify.certificate') }}" class="hover:text-emerald-200 transition flex items-center gap-1.5 font-bold text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ __('Verify Certificate') }}
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('whistleblower') }}" class="text-rose-400 hover:text-rose-300 transition flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            {{ __('Confidential Whistleblower') }}
                        </a>
                    </li>
                    <li><a href="{{ route('whistleblower.track') }}" class="hover:text-slate-200 transition text-xs text-slate-400">{{ __('Track Complaint Ticket') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-[#E5B51B] transition">{{ __('Contact Secretariat') }}</a></li>
                </ul>
            </div>
        </div>

        <!-- Fraud Warning Callout Banner -->
        <div class="bg-[#001428] border border-[#E5B51B]/40 rounded-2xl p-5 mb-8 text-xs text-slate-200 leading-relaxed flex flex-col sm:flex-row items-start sm:items-center gap-3.5 shadow-lg">
            <div class="w-9 h-9 rounded-xl bg-[#E5B51B]/20 text-[#E5B51B] flex items-center justify-center shrink-0 border border-[#E5B51B]/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <strong class="font-black text-[#E5B51B] uppercase tracking-wide block sm:inline mr-1">{{ __('Official Anti-Fraud Notice:') }}</strong>
                {{ __(\App\Models\Setting::get('fraud_warning', 'TEVDA never requests cash deposits or mobile money transfers to private personal numbers. All association payments must have an official system invoice and receipt. TEVDA does not guarantee instant loans, grants, or vehicle allocations without verified vetting.')) }}
            </div>
        </div>

        <!-- Bottom bar with Language Switcher Quick Links -->
        <div class="border-t border-[#002c59] pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-400">
            <p>&copy; {{ date('Y') }} {{ __('Tanzania Electric Vehicle Drivers Association (TEVDA)') }}. {{ __('All rights reserved.') }}</p>
            
            <!-- Quick Language Switcher in Footer -->
            <div class="flex items-center gap-3 text-xs">
                <span class="text-slate-400 font-medium">{{ __('Language') }}:</span>
                <a href="{{ route('locale.switch', 'en') }}" class="hover:text-[#E5B51B] transition {{ app()->getLocale() === 'en' ? 'text-[#E5B51B] font-bold' : 'text-slate-400' }}">🇬🇧 EN</a>
                <span class="text-slate-700">|</span>
                <a href="{{ route('locale.switch', 'sw') }}" class="hover:text-[#E5B51B] transition {{ app()->getLocale() === 'sw' ? 'text-[#E5B51B] font-bold' : 'text-slate-400' }}">🇹🇿 SW</a>
                <span class="text-slate-700">|</span>
                <a href="{{ route('locale.switch', 'zh') }}" class="hover:text-[#E5B51B] transition {{ app()->getLocale() === 'zh' ? 'text-[#E5B51B] font-bold' : 'text-slate-400' }}">🇨🇳 中文</a>
            </div>

            <div class="flex flex-wrap gap-4 sm:gap-6 text-slate-400">
                <a href="{{ route('privacy') }}" class="hover:text-[#E5B51B] transition">{{ __('Privacy Policy') }}</a>
                <a href="{{ route('terms') }}" class="hover:text-[#E5B51B] transition">{{ __('Terms of Use') }}</a>
                <a href="{{ route('cookies') }}" class="hover:text-[#E5B51B] transition">{{ __('Cookies Policy') }}</a>
                <a href="{{ route('code_of_conduct') }}" class="hover:text-[#E5B51B] transition">{{ __('Code of Conduct') }}</a>
            </div>
        </div>
    </div>
</footer>
