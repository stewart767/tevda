@extends('layouts.app')

@section('title', 'About TEVDA — Vision, Mission, SMART Principles & Institutional Profile')

@section('content')
<!-- Page Header -->
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">About TEVDA</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Institutional Heritage & Purpose</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Discover how the Tanzania Electric Vehicle Drivers Association is pioneering clean mobility, driver empowerment, and sustainable transport leadership.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Who We Are & Founding Story -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <div class="lg:col-span-7 space-y-6">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200/60">Founding Heritage</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-heading">The Genesis of TEVDA</h2>
                <p class="text-slate-600 text-sm leading-relaxed">
                    As electric three-wheelers (bajaj), electric motorcycles, and zero-emission commercial vehicles began transforming Tanzania’s urban corridors, frontline drivers encountered unique operational hurdles: high-voltage safety learning curves, battery swapping infrastructure nuances, lack of formal representation, and barriers to asset financing.
                </p>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Recognizing the urgent need for structured driver organization, certified technical training, and institutional advocacy, the founding leadership established the **Tanzania Electric Vehicle Drivers Association (TEVDA)** to build a dignified, professional, and prosperous clean-transport workforce.
                </p>
                <div class="p-6 bg-emerald-50/70 rounded-3xl border border-emerald-200/80 text-emerald-950 text-xs sm:text-sm font-semibold italic flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <span>"SMART DRIVERS SMART MOBILITY — Transforming Tanzania’s commercial transport workforce into skilled, creditworthy champions of clean energy."</span>
                </div>
            </div>

            <!-- Founding Leaders Card Spotlight -->
            <div class="lg:col-span-5">
                <div class="bg-slate-950 rounded-3xl p-8 text-white shadow-2xl space-y-6 border border-slate-800 relative overflow-hidden">
                    <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-emerald-500/10 rounded-full blur-2xl pointer-events-none"></div>

                    <div class="border-b border-slate-800 pb-4 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] font-extrabold text-emerald-400 uppercase tracking-widest block">Executive Governance</span>
                            <h3 class="text-lg font-black font-heading mt-0.5 text-white">Founding Leadership</h3>
                        </div>
                        <a href="{{ route('leadership') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-bold flex items-center gap-1">
                            <span>All Leaders</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse ($foundingLeaders->take(3) as $leader)
                            <div class="flex items-center gap-3.5 p-3 rounded-2xl bg-slate-900/80 border border-slate-800">
                                <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white font-black text-sm flex items-center justify-center shrink-0 overflow-hidden shadow-md">
                                    @if ($leader->photo_url)
                                        <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                        <span class="hidden font-black text-sm">{{ substr($leader->name, 0, 2) }}</span>
                                    @else
                                        <span>{{ substr($leader->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-white font-heading">{{ $leader->name }}</h4>
                                    <p class="text-xs text-emerald-400">{{ $leader->position }}</p>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $leader->founding_position ?? 'Founding Council' }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400">Founding executive council profiles registered under TEVDA charter.</p>
                        @endforelse
                    </div>

                    <div class="pt-4 border-t border-slate-800 flex justify-between items-center text-xs text-slate-400">
                        <span>Office: Sinza Mori, Dar es Salaam</span>
                        <a href="{{ route('leadership') }}" class="text-emerald-400 font-bold hover:underline">Full Governance &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vision, Mission & Values Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 pt-8 border-t border-slate-100">
            <!-- Vision -->
            <div class="glass-card p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center font-bold mb-6 shadow-md shadow-emerald-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 font-heading mb-3">Our Vision</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ \App\Models\Setting::get('vision', 'To be the leading national association fostering a prosperous, skilled, and safe commercial electric vehicle driver community driving Tanzania\'s clean-energy transport transition.') }}
                    </p>
                </div>
            </div>

            <!-- Mission -->
            <div class="glass-card p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-600 text-white flex items-center justify-center font-bold mb-6 shadow-md shadow-cyan-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 font-heading mb-3">Our Mission</h3>
                    <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
                        {{ \App\Models\Setting::get('mission', 'To represent, train, protect, and empower commercial electric vehicle drivers and operators across Tanzania by facilitating access to green finance, technical skills, digital tools, fair operating conditions, and strategic national partnerships.') }}
                    </p>
                </div>
            </div>

            <!-- Values -->
            <div class="glass-card p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-bold mb-6 shadow-md shadow-indigo-600/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 font-heading mb-3">Core Values</h3>
                    <ul class="text-xs sm:text-sm text-slate-600 space-y-2">
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-emerald-600"></span><strong>Integrity:</strong> Transparent governance.</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-cyan-600"></span><strong>Safety:</strong> Uncompromising vehicle & road standards.</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-indigo-600"></span><strong>Inclusivity:</strong> Empowering youth and women drivers.</li>
                        <li class="flex items-center gap-2"><span class="w-2 h-2 rounded-full bg-amber-600"></span><strong>Sustainability:</strong> Accelerating green mobility.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SMART Principles Deep-Dive -->
        <div class="bg-slate-950 text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3.5 py-1.5 rounded-xl border border-emerald-800">
                    {{ \App\Models\Setting::get('smart_principles_badge', 'Operational Philosophy') }}
                </span>
                <h2 class="text-3xl font-black font-heading mt-3 text-white">
                    {{ \App\Models\Setting::get('smart_principles_title', 'The SMART Principles of TEVDA') }}
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-2">
                    {{ \App\Models\Setting::get('smart_principles_subtitle', 'The foundational pillars guiding every TEVDA driver, operator, and operational standard') }}
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- S -->
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 hover:border-emerald-500/50 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-3xl font-black text-emerald-400 font-heading block">S</span>
                        <span class="text-[10px] font-extrabold text-emerald-400 bg-emerald-950/80 px-2 py-0.5 rounded-full border border-emerald-800">Usalama</span>
                    </div>
                    <h3 class="font-black text-base text-white mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_s_title', 'Safety') }}
                    </h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ \App\Models\Setting::get('smart_s_desc', 'Rigorous defensive driving, pedestrian safety in silent EVs, and high-voltage battery operating protocols.') }}
                    </p>
                </div>

                <!-- M -->
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 hover:border-cyan-500/50 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-3xl font-black text-cyan-400 font-heading block">M</span>
                        <span class="text-[10px] font-extrabold text-cyan-400 bg-cyan-950/80 px-2 py-0.5 rounded-full border border-cyan-800">Teknolojia</span>
                    </div>
                    <h3 class="font-black text-base text-white mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_m_title', 'Modern Tech') }}
                    </h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ \App\Models\Setting::get('smart_m_desc', 'Embracing electric drivetrains, rapid battery swapping networks, charging telemetry, and digital mobility apps.') }}
                    </p>
                </div>

                <!-- A -->
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 hover:border-indigo-500/50 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-3xl font-black text-indigo-400 font-heading block">A</span>
                        <span class="text-[10px] font-extrabold text-indigo-400 bg-indigo-950/80 px-2 py-0.5 rounded-full border border-indigo-800">Uwajibikaji</span>
                    </div>
                    <h3 class="font-black text-base text-white mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_a_title', 'Accountability') }}
                    </h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ \App\Models\Setting::get('smart_a_desc', 'Strict adherence to Tanzanian transport regulations (LATRA), member welfare accountability, and ethical governance.') }}
                    </p>
                </div>

                <!-- R -->
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 hover:border-amber-500/50 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-3xl font-black text-amber-400 font-heading block">R</span>
                        <span class="text-[10px] font-extrabold text-amber-400 bg-amber-950/80 px-2 py-0.5 rounded-full border border-amber-800">Heshima</span>
                    </div>
                    <h3 class="font-black text-base text-white mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_r_title', 'Respect') }}
                    </h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ \App\Models\Setting::get('smart_r_desc', 'Upholding passenger dignity, mutual driver solidarity, ethical fare practices, and community road respect.') }}
                    </p>
                </div>

                <!-- T -->
                <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 hover:border-teal-500/50 transition">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-3xl font-black text-teal-400 font-heading block">T</span>
                        <span class="text-[10px] font-extrabold text-teal-400 bg-teal-950/80 px-2 py-0.5 rounded-full border border-teal-800">Umoja</span>
                    </div>
                    <h3 class="font-black text-base text-white mb-2 font-heading">
                        {{ \App\Models\Setting::get('smart_t_title', 'Teamwork') }}
                    </h3>
                    <p class="text-xs text-slate-300 leading-relaxed">
                        {{ \App\Models\Setting::get('smart_t_desc', 'Collaborating with operators, charging companies, regulators, and financiers to build an inclusive clean mobility ecosystem.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- FAQs Section -->
        <div class="pt-8">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200/60">Common Questions</span>
                <h2 class="text-3xl font-black text-slate-900 font-heading mt-3">Frequently Asked Questions</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($faqs as $faq)
                    <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs">
                        <h3 class="font-black text-base text-slate-900 mb-2 font-heading">{{ $faq->question }}</h3>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $faq->answer }}</p>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
