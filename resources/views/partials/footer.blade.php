<footer class="bg-slate-950 text-slate-300 pt-16 pb-12 border-t border-slate-800 relative overflow-hidden">
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-cyan-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-10 lg:gap-8 mb-12">
            <!-- Col 1: Brand & Association Identity -->
            <div class="lg:col-span-2 space-y-4">
                <div class="flex items-center gap-3">
                    @if(\App\Models\Setting::hasCustomLogo())
                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-12 w-auto max-w-[170px] object-contain" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                        <div class="hidden w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-black text-xl shadow-lg">
                            <span class="tracking-tighter">TEV</span>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-black text-xl shadow-lg">
                            <span class="tracking-tighter">TEV</span>
                        </div>
                    @endif
                    <div>
                        <span class="text-xl font-black tracking-tight text-white block font-heading">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }}</span>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-emerald-400 block">Tanzania Electric Vehicle Drivers Association</span>
                    </div>
                </div>

                <div class="inline-flex items-center gap-2 bg-emerald-950/80 border border-emerald-800/80 px-3 py-1.5 rounded-xl text-emerald-300 text-xs font-bold tracking-wider shadow-xs">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    SMART DRIVERS SMART MOBILITY
                </div>

                <p class="text-slate-400 text-xs sm:text-sm leading-relaxed pr-6">
                    Representing, upskilling, and advocating for commercial electric vehicle drivers, battery-swapping operators, technicians, and green mobility innovators across Tanzania.
                </p>

                <div class="pt-2 text-xs text-slate-400 space-y-2">
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span>+255 757 700 401</span>
                    </p>
                    <p class="flex items-center gap-2.5">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@tevda.or.tz</span>
                    </p>
                    <p class="text-[11px] text-slate-500 pt-1">Secretariat Office Hours: Monday – Friday, 8:00 a.m. – 5:00 p.m. EAT</p>
                </div>
            </div>

            <!-- Col 2: Association -->
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-widest mb-4 font-heading text-emerald-400">Association</h4>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-emerald-400 transition">About TEVDA & Principles</a></li>
                    <li><a href="{{ route('leadership') }}" class="hover:text-emerald-400 transition">Leadership & Governance</a></li>
                    <li><a href="{{ route('membership.info') }}" class="hover:text-emerald-400 transition">Membership Categories</a></li>
                    <li><a href="{{ route('partners') }}" class="hover:text-emerald-400 transition">Partners & Collaborations</a></li>
                    <li><a href="{{ route('resources') }}" class="hover:text-emerald-400 transition">Resource Center & Docs</a></li>
                    <li><a href="{{ route('news') }}" class="hover:text-emerald-400 transition">News & Media Statements</a></li>
                </ul>
            </div>

            <!-- Col 3: Programmes & Projects -->
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-widest mb-4 font-heading text-cyan-400">Programmes & EV</h4>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li><a href="{{ route('programmes') }}" class="hover:text-emerald-400 transition">Driver Training Modules</a></li>
                    <li><a href="{{ route('projects') }}" class="hover:text-emerald-400 transition">Strategic EV Projects</a></li>
                    <li><a href="{{ route('projects.show', '50-electric-three-wheeler-programme') }}" class="hover:text-emerald-400 transition text-emerald-400 font-semibold">50 Three-Wheeler Programme</a></li>
                    <li><a href="{{ route('opportunities') }}" class="hover:text-emerald-400 transition">Asset Financing & Grants</a></li>
                    <li><a href="{{ route('opportunities') }}" class="hover:text-emerald-400 transition">Commercial Fleet Jobs</a></li>
                </ul>
            </div>

            <!-- Col 4: Public Verification & Integrity -->
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-widest mb-4 font-heading text-amber-400">Integrity & Verification</h4>
                <ul class="space-y-2.5 text-xs sm:text-sm">
                    <li>
                        <a href="{{ route('verify.membership') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5 font-bold text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Verify Member ID
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('verify.certificate') }}" class="hover:text-emerald-300 transition flex items-center gap-1.5 font-bold text-emerald-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Verify Certificate
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('whistleblower') }}" class="text-rose-400 hover:text-rose-300 transition flex items-center gap-1.5 font-bold">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Confidential Whistleblower
                        </a>
                    </li>
                    <li><a href="{{ route('whistleblower.track') }}" class="hover:text-slate-200 transition text-xs text-slate-400">Track Complaint Ticket</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-emerald-400 transition">Contact Secretariat</a></li>
                </ul>
            </div>
        </div>

        <!-- Fraud Warning Callout Banner -->
        <div class="bg-gradient-to-r from-amber-950/60 to-slate-900/90 border border-amber-500/40 rounded-2xl p-5 mb-8 text-xs text-amber-200/90 leading-relaxed flex flex-col sm:flex-row items-start sm:items-center gap-3.5 shadow-lg">
            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0 border border-amber-500/40">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
                <strong class="font-black text-amber-300 uppercase tracking-wide block sm:inline mr-1">Official Anti-Fraud Notice:</strong>
                {{ \App\Models\Setting::get('fraud_warning', 'TEVDA never requests cash deposits or mobile money transfers to private personal numbers. All association payments must have an official system invoice and receipt. TEVDA does not guarantee instant loans, grants, or vehicle allocations without verified vetting.') }}
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="border-t border-slate-800/80 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500">
            <p>&copy; {{ date('Y') }} Tanzania Electric Vehicle Drivers Association (TEVDA). All rights reserved.</p>
            <div class="flex flex-wrap gap-4 sm:gap-6 text-slate-400">
                <a href="{{ route('privacy') }}" class="hover:text-emerald-400 transition">Privacy Policy</a>
                <a href="{{ route('terms') }}" class="hover:text-emerald-400 transition">Terms of Use</a>
                <a href="{{ route('cookies') }}" class="hover:text-emerald-400 transition">Cookies Policy</a>
                <a href="{{ route('code_of_conduct') }}" class="hover:text-emerald-400 transition">Code of Conduct</a>
            </div>
        </div>
    </div>
</footer>
