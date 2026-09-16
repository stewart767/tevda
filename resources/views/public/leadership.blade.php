@extends('layouts.app')

@section('title', 'Leadership & Governance — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Association Governance</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Leadership & Governance</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">The administrative, executive, and founding leadership structures guiding the Tanzania Electric Vehicle Drivers Association.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Founding Leadership Section -->
        <div>
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-700 bg-emerald-50 px-3.5 py-1.5 rounded-xl border border-emerald-200/60">Executive Directorate</span>
                <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-heading mt-3">Founding & Executive Leadership</h2>
                <p class="text-slate-600 text-xs sm:text-sm mt-1">The executive officers who established and guide the institutional framework of TEVDA.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach ($foundingLeaders as $leader)
                    <div class="glass-card p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between group">
                        <div>
                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-16 h-16 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-black text-2xl shadow-lg shrink-0 overflow-hidden border-2 border-emerald-500/30">
                                    @if ($leader->photo_url)
                                        <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                        <span class="hidden font-black text-xl">{{ strtoupper(substr($leader->name, 0, 2)) }}</span>
                                    @else
                                        <span>{{ strtoupper(substr($leader->name, 0, 2)) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <span class="inline-block text-[10px] uppercase font-extrabold text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full mb-1">
                                        {{ $leader->founding_position ?? 'Executive Leader' }}
                                    </span>
                                    <h3 class="font-black text-lg text-slate-900 font-heading leading-tight">{{ $leader->name }}</h3>
                                    <p class="text-xs text-emerald-700 font-bold mt-0.5">{{ $leader->position }}</p>
                                </div>
                            </div>
                            
                            <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $leader->biography }}</p>

                            @if ($leader->responsibilities)
                                <div class="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 text-xs text-slate-700 mb-4">
                                    <strong class="text-slate-900 block mb-1 font-bold">Key Responsibilities:</strong>
                                    {{ $leader->responsibilities }}
                                </div>
                            @endif
                        </div>

                        <div class="pt-4 border-t border-slate-200 flex justify-between items-center text-xs text-slate-500">
                            <span>Office Contact:</span>
                            <span class="font-bold text-slate-700">{{ $leader->official_office_contact ?? 'info@tevda.or.tz' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- National Council Leaders (if any) -->
        @if (isset($nationalLeaders) && $nationalLeaders->count() > 0)
            <div>
                <div class="text-center max-w-3xl mx-auto mb-10">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-cyan-700 bg-cyan-50 px-3.5 py-1.5 rounded-xl border border-cyan-200/60">National Council</span>
                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 font-heading mt-2">National Standing Committee Leaders</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($nationalLeaders as $leader)
                        <div class="glass-card p-6 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
                            <div class="flex items-center gap-3.5 mb-3">
                                <div class="w-12 h-12 rounded-xl bg-cyan-700 text-white flex items-center justify-center font-black text-sm shrink-0 overflow-hidden">
                                    @if ($leader->photo_url)
                                        <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                        <span class="hidden font-bold">{{ substr($leader->name, 0, 2) }}</span>
                                    @else
                                        <span>{{ substr($leader->name, 0, 2) }}</span>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 font-heading">{{ $leader->name }}</h4>
                                    <p class="text-xs text-cyan-700 font-medium">{{ $leader->position }}</p>
                                </div>
                            </div>
                            <p class="text-xs text-slate-600 line-clamp-3">{{ $leader->biography }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Governance Hierarchy & Organs -->
        <div class="bg-slate-950 text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3.5 py-1.5 rounded-xl border border-emerald-800">Organizational Architecture</span>
                <h2 class="text-3xl font-black font-heading mt-3 text-white">Governance Bodies & Tiered Representation</h2>
                <p class="text-slate-300 text-xs sm:text-sm mt-1">Structured tier representation from National Assemblies down to local Grassroots Charging Hub Branches.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($governanceBodies as $body)
                    <div class="bg-slate-900/90 p-6 rounded-2xl border border-slate-800 flex flex-col justify-between hover:border-emerald-500/50 transition">
                        <div>
                            <span class="text-[10px] uppercase font-bold text-emerald-400 bg-emerald-950 px-2.5 py-0.5 rounded-full border border-emerald-800">
                                {{ ucfirst($body->level) }} Level Organ
                            </span>
                            <h3 class="font-black text-lg text-white font-heading mt-3 mb-2">{{ $body->name }}</h3>
                            <p class="text-xs text-slate-300 leading-relaxed">{{ $body->description }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
