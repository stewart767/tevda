@extends('layouts.app')

@section('title', 'Leadership & Governance — TEVDA')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-[#E5B51B] bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-[#E5B51B]/30">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>Association Governance</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white tracking-tight">Leadership & Governance</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">The administrative, executive, and founding leadership structures guiding the Tanzania Electric Vehicle Drivers Association.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Founding & Board Leadership Section -->
        <div>
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#108010] bg-[#108010]/10 px-3.5 py-1.5 rounded-xl border border-[#108010]/20">National Board & Founders</span>
                <h2 class="text-3xl sm:text-4xl font-black text-[#002040] font-heading mt-3">Baraza la Taifa na Uongozi Mwanzilishi</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">Viongozi wakuu wa Baraza la Taifa na waasisi walioanzisha na kusimamia dira na sera za TEVDA.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($foundingLeaders as $leader)
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-16 h-16 rounded-2xl bg-[#002040] text-white flex items-center justify-center font-black text-2xl shadow-lg shrink-0 overflow-hidden border-2 border-[#E5B51B]/40">
                                    @if ($leader->photo_url)
                                        <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                        <span class="hidden font-black text-xl">{{ strtoupper(substr($leader->name, 0, 2)) }}</span>
                                    @else
                                        <span>{{ strtoupper(substr($leader->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="inline-block text-[10px] uppercase font-extrabold text-[#108010] bg-[#108010]/10 px-2.5 py-0.5 rounded-full mb-1">
                                        {{ $leader->founding_position ?? 'Executive Leader' }}
                                    </span>
                                    <h3 class="font-black text-lg text-[#002040] font-heading leading-tight">{{ $leader->name }}</h3>
                                    <p class="text-xs text-[#108010] font-bold mt-0.5">{{ $leader->position }}</p>
                                </div>
                            </div>
                            
                            <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $leader->biography }}</p>

                            @if ($leader->responsibilities)
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-700 mb-4">
                                    <strong class="text-[#002040] block mb-1 font-bold">Key Responsibilities:</strong>
                                    {{ $leader->responsibilities }}
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center text-xs text-slate-500">
                            <span>Office Contact:</span>
                            <span class="font-bold text-[#002040]">{{ $leader->official_office_contact ?? 'info@tevda.or.tz' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- National Executive Secretariat & Directorate Section -->
        @if (isset($nationalLeaders) && $nationalLeaders->count() > 0)
            <div>
                <div class="text-center max-w-3xl mx-auto mb-12">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-[#108010] bg-[#108010]/10 px-3.5 py-1.5 rounded-xl border border-[#108010]/20">Executive Directorate</span>
                    <h2 class="text-2xl sm:text-4xl font-black text-[#002040] font-heading mt-2">Wakurugenzi na Watendaji Wakuu</h2>
                    <p class="text-slate-600 text-xs sm:text-sm mt-1">Sekretarieti Kuu ya Utendaji inayoongozwa na Mkurugenzi Mtendaji pamoja na Wakurugenzi wa Idara za TEVDA.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($nationalLeaders as $leader)
                        @php
                            $isCEO = str_contains(strtolower($leader->position), 'mtendaji') || str_contains(strtolower($leader->position), 'executive director');
                            $isPending = str_contains(strtolower($leader->name), 'wazi') || str_contains(strtolower($leader->name), 'pending');
                        @endphp
                        <div class="bg-white p-6 rounded-3xl border {{ $isCEO ? 'border-[#108010] bg-gradient-to-br from-[#108010]/5 to-white shadow-md' : 'border-slate-200/80 shadow-xs' }} flex flex-col justify-between hover:border-[#108010]/50 transition-all duration-200 group">
                            <div>
                                <div class="flex items-center gap-3.5 mb-4">
                                    <div class="w-14 h-14 rounded-2xl {{ $isCEO ? 'bg-[#002040] text-white border-2 border-[#E5B51B]' : ($isPending ? 'bg-slate-100 text-slate-400 border border-slate-200' : 'bg-[#002040] text-white') }} flex items-center justify-center font-black text-base shrink-0 overflow-hidden shadow-xs">
                                        @if ($leader->photo_url)
                                            <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                            <span class="hidden font-bold">{{ substr($leader->name, 0, 2) }}</span>
                                        @else
                                            <span>{{ $isPending ? 'TEV' : strtoupper(substr($leader->name, 0, 2)) }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        @if($isCEO)
                                            <span class="inline-block text-[9.5px] uppercase font-extrabold text-[#108010] bg-[#108010]/10 px-2 py-0.5 rounded-full mb-1">
                                                Uongozi wa Utendaji Mkuu
                                            </span>
                                        @elseif($isPending)
                                            <span class="inline-block text-[9.5px] uppercase font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-full mb-1">
                                                Nafasi ya Idara
                                            </span>
                                        @else
                                            <span class="inline-block text-[9.5px] uppercase font-bold text-[#002040] bg-slate-100 px-2 py-0.5 rounded-full mb-1">
                                                Mkurugenzi wa Idara
                                            </span>
                                        @endif
                                        <h4 class="font-extrabold text-base text-[#002040] font-heading leading-tight truncate" title="{{ $leader->name }}">
                                            {{ $leader->name }}
                                        </h4>
                                        <p class="text-xs {{ $isCEO ? 'text-[#108010] font-extrabold' : 'text-slate-600 font-semibold' }} mt-0.5">
                                            {{ $leader->position }}
                                        </p>
                                    </div>
                                </div>

                                <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">
                                    {{ $leader->biography }}
                                </p>

                                @if ($leader->responsibilities)
                                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-[11px] text-slate-600 mb-4">
                                        <strong class="text-[#002040] block mb-0.5 font-bold">Majukumu Makuu:</strong>
                                        <p class="line-clamp-2">{{ $leader->responsibilities }}</p>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-[11px] text-slate-400">
                                <span>Mawasiliano:</span>
                                <span class="font-semibold text-slate-700 truncate max-w-[170px]">{{ $leader->official_office_contact ?? 'info@tevda.or.tz' }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Governance Hierarchy & Organs -->
        <div class="bg-[#002040] text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="text-center max-w-3xl mx-auto mb-12 relative z-10">
                <span class="text-xs font-extrabold uppercase tracking-widest text-[#E5B51B] bg-white/10 px-3.5 py-1.5 rounded-xl border border-[#E5B51B]/30">Organizational Architecture</span>
                <h2 class="text-3xl font-black font-heading mt-3 text-white">Governance Bodies & Tiered Representation</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-1">Structured tier representation from National Assemblies down to local Grassroots Charging Hub Branches.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 relative z-10">
                @foreach ($governanceBodies as $body)
                    <div class="bg-slate-900/80 p-6 rounded-2xl border border-slate-700/60 flex flex-col justify-between hover:border-[#108010] transition group">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-[#E5B51B] bg-white/10 px-2.5 py-0.5 rounded-full border border-white/10">
                                {{ ucfirst($body->level) }} Level Organ
                            </span>
                            <h3 class="text-lg font-black text-white font-heading mt-3 mb-2">{{ $body->name }}</h3>
                            <p class="text-xs text-slate-300 leading-relaxed mb-4">{{ $body->description }}</p>
                        </div>
                        <div class="pt-3 border-t border-slate-800 text-xs text-slate-400">
                            <strong>Mandate:</strong> {{ $body->mandate ?? 'Institutional statutory oversight & member representation.' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
