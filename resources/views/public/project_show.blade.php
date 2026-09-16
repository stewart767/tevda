@extends('layouts.app')

@section('title', $project->title . ' — TEVDA Projects')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3 py-1 rounded-lg border border-emerald-800/80">
                {{ $project->category->name }}
            </span>
            <span class="text-xs font-bold px-3 py-1 rounded-lg {{ $project->project_status === 'open_for_applications' ? 'bg-emerald-500 text-slate-950' : 'bg-amber-500/20 text-amber-300 border border-amber-500/40' }}">
                Status: {{ ucwords(str_replace('_', ' ', $project->project_status)) }}
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading text-white leading-tight">{{ $project->title }}</h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-3 flex items-center gap-2">
            <span>{{ $project->location }}</span>
            <span>•</span>
            <span>Target: {{ $project->target_beneficiaries_count }} Drivers</span>
        </p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        @if ($project->featured_image_url)
            <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200 h-72 sm:h-96 w-full">
                <img src="{{ $project->featured_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Summary Callout -->
        <div class="p-8 bg-emerald-50/70 rounded-3xl border border-emerald-200/80 text-slate-800 text-sm sm:text-base leading-relaxed">
            <strong class="text-emerald-950 block mb-2 font-black font-heading text-lg">Executive Summary</strong>
            {{ $project->summary }}
        </div>

        <!-- Full Description -->
        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
            {!! nl2br(e($project->description)) !!}
        </div>

        <!-- Project Meta Table -->
        <div class="glass-card rounded-3xl p-8 border border-slate-200/80 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs sm:text-sm text-slate-700 shadow-xs">
            <div>
                <span class="text-slate-400 uppercase font-extrabold tracking-wider block mb-1 text-[11px]">Target Geography</span>
                <p class="font-bold text-slate-900">{{ $project->location }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-extrabold tracking-wider block mb-1 text-[11px]">Funding Status</span>
                <p class="font-bold text-slate-900">{{ ucwords(str_replace('_', ' ', $project->funding_status)) }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-extrabold tracking-wider block mb-1 text-[11px]">Stakeholder Framework</span>
                <p class="font-bold text-slate-900">{{ $project->partner_organisations ?? 'Under Stakeholder Engagement' }}</p>
            </div>
        </div>

        <!-- Application Action Box -->
        <div class="p-8 sm:p-10 bg-slate-950 rounded-3xl text-white shadow-2xl flex flex-col md:flex-row justify-between items-center gap-6 border border-slate-800">
            <div>
                <h3 class="text-xl sm:text-2xl font-black font-heading mb-1 text-white">Programme Participation & Asset Intake</h3>
                @if ($project->project_status === 'open_for_applications')
                    <p class="text-xs sm:text-sm text-emerald-400">Applications are currently OPEN for verified TEVDA members.</p>
                @else
                    <p class="text-xs sm:text-sm text-amber-300">Public application intake is currently pending official funding & asset confirmation.</p>
                @endif
            </div>

            <div class="shrink-0">
                @if ($project->project_status === 'open_for_applications')
                    @auth
                        <a href="{{ route('portal.projects') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-7 py-3.5 rounded-2xl text-xs sm:text-sm transition shadow-lg hover:shadow-emerald-500/30">
                            Apply in Member Portal
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-7 py-3.5 rounded-2xl text-xs sm:text-sm transition shadow-lg hover:shadow-emerald-500/30">
                            Register as Member to Apply
                        </a>
                    @endauth
                @else
                    <button type="button" disabled class="bg-slate-900 text-slate-400 font-bold px-6 py-3 rounded-xl text-xs cursor-not-allowed border border-slate-800">
                        Applications Pending
                    </button>
                @endif
            </div>
        </div>

        <!-- Back Button -->
        <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
            <a href="{{ route('projects') }}" class="text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Strategic Projects Directory</span>
            </a>
        </div>

    </div>
</div>
@endsection
