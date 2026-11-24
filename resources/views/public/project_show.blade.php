@extends('layouts.app')

@section('title', $project->title . ' — TEVDA Projects')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#E5B51B] bg-white/10 px-3 py-1 rounded-lg border border-[#E5B51B]/30">
                {{ $project->category->name }}
            </span>
            <span class="text-xs font-bold px-3 py-1 rounded-lg {{ $project->project_status === 'open_for_applications' ? 'bg-[#108010] text-white' : 'bg-[#E5B51B] text-[#002040]' }}">
                Status: {{ ucwords(str_replace('_', ' ', $project->project_status)) }}
            </span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading text-white leading-tight tracking-tight">{{ $project->title }}</h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-3 flex items-center gap-2">
            <span>{{ $project->location }}</span>
            <span>•</span>
            <span>Target: {{ $project->target_beneficiaries_count }} Drivers</span>
        </p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        @if ($project->featured_image_url)
            <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200 h-72 sm:h-96 w-full">
                <img src="{{ $project->featured_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <!-- Summary Callout -->
        <div class="p-8 bg-[#108010]/5 rounded-3xl border border-[#108010]/20 text-slate-800 text-sm sm:text-base leading-relaxed">
            <strong class="text-[#002040] block mb-2 font-black font-heading text-lg">Executive Summary</strong>
            {{ $project->summary }}
        </div>

        <!-- Full Description -->
        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
            {!! nl2br(e($project->description)) !!}
        </div>

        <!-- Project Meta Table -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 grid grid-cols-1 md:grid-cols-3 gap-6 text-xs sm:text-sm text-slate-700 shadow-sm">
            <div>
                <span class="text-slate-400 uppercase font-extrabold tracking-wider block mb-1 text-[11px]">Target Geography</span>
                <p class="font-bold text-[#002040]">{{ $project->location }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-extrabold tracking-wider block mb-1 text-[11px]">Funding Status</span>
                <p class="font-bold text-[#002040]">{{ ucwords(str_replace('_', ' ', $project->funding_status)) }}</p>
            </div>
            <div>
                <span class="text-slate-400 uppercase font-extrabold tracking-wider block mb-1 text-[11px]">Stakeholder Framework</span>
                <p class="font-bold text-[#002040]">{{ $project->partner_organisations ?? 'Under Stakeholder Engagement' }}</p>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
            <a href="{{ route('projects') }}" class="text-xs font-bold text-[#108010] hover:text-[#002040] flex items-center gap-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to All Projects</span>
            </a>
            @if ($project->project_status === 'open_for_applications')
                <a href="{{ route('register') }}" class="bg-[#108010] hover:bg-[#0c660c] text-white font-bold px-6 py-3 rounded-xl text-xs transition shadow-md shadow-[#108010]/20">
                    Apply as Registered Member
                </a>
            @endif
        </div>

    </div>
</div>
@endsection
