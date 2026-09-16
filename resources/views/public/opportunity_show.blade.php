@extends('layouts.app')

@section('title', $opportunity->title . ' — TEVDA Opportunities')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-indigo-300 bg-indigo-950/80 px-3 py-1 rounded-lg border border-indigo-800/80">
                {{ $opportunity->category->name }}
            </span>
            @if ($opportunity->deadline)
                <span class="text-xs font-bold px-3 py-1 rounded-lg bg-slate-900/80 text-slate-200 border border-slate-700">
                    Deadline: {{ $opportunity->deadline->format('d F Y') }}
                </span>
            @endif
        </div>
        <h1 class="text-3xl sm:text-4xl font-black font-heading leading-tight text-white">{{ $opportunity->title }}</h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-3">{{ $opportunity->provider_name }} • {{ $opportunity->location }}</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Summary Callout -->
        <div class="p-8 bg-indigo-50/60 rounded-3xl border border-indigo-100 text-slate-800 text-sm sm:text-base leading-relaxed">
            <strong class="text-indigo-950 block mb-2 font-black font-heading text-lg">Opportunity Summary</strong>
            {{ $opportunity->summary }}
        </div>

        <!-- Full Description -->
        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
            <h3 class="font-black text-xl text-slate-900 font-heading">Detailed Description & Scope</h3>
            {!! nl2br(e($opportunity->description)) !!}
        </div>

        <!-- Eligibility & Criteria -->
        @if ($opportunity->eligibility_criteria)
            <div class="glass-card rounded-3xl p-8 border border-slate-200/80 shadow-xs space-y-3">
                <h3 class="font-black text-lg text-slate-900 font-heading flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Eligibility Criteria & Member Qualifications</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $opportunity->eligibility_criteria }}</p>
            </div>
        @endif

        <!-- Action Box -->
        <div class="p-8 sm:p-10 bg-slate-950 rounded-3xl text-white shadow-2xl flex flex-col sm:flex-row justify-between items-center gap-6 border border-slate-800">
            <div>
                <h3 class="text-xl sm:text-2xl font-black font-heading mb-1 text-white">How to Apply</h3>
                <p class="text-xs sm:text-sm text-slate-400">
                    @if ($opportunity->application_type === 'internal')
                        Direct intake through TEVDA Member Portal for active certified drivers.
                    @else
                        Official external partner application process.
                    @endif
                </p>
            </div>

            <div>
                @if ($opportunity->application_type === 'internal')
                    @auth
                        <a href="{{ route('portal.opportunities') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-7 py-3.5 rounded-2xl text-xs sm:text-sm transition shadow-lg">
                            Apply via Portal
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-7 py-3.5 rounded-2xl text-xs sm:text-sm transition shadow-lg">
                            Register to Apply
                        </a>
                    @endauth
                @elseif ($opportunity->external_url)
                    <a href="{{ $opportunity->external_url }}" target="_blank" rel="noopener noreferrer" class="bg-indigo-600 hover:bg-indigo-500 text-white font-bold px-7 py-3.5 rounded-2xl text-xs sm:text-sm transition shadow-lg inline-flex items-center gap-2">
                        <span>External Portal</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    </a>
                @endif
            </div>
        </div>

        <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
            <a href="{{ route('opportunities') }}" class="text-xs sm:text-sm font-bold text-indigo-700 hover:text-indigo-800 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Opportunities Directory</span>
            </a>
        </div>

    </div>
</div>
@endsection
