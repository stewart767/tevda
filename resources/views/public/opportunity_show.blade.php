@extends('layouts.app')

@section('title', $opportunity->title . ' — TEVDA Opportunities')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-wrap items-center gap-2 mb-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#E5B51B] bg-white/10 px-3 py-1 rounded-lg border border-[#E5B51B]/30">
                {{ $opportunity->category->name }}
            </span>
            @if ($opportunity->deadline)
                <span class="text-xs font-bold px-3 py-1 rounded-lg bg-slate-900/80 text-slate-200 border border-slate-700">
                    Deadline: {{ $opportunity->deadline->format('d F Y') }}
                </span>
            @endif
        </div>
        <h1 class="text-3xl sm:text-4xl font-black font-heading leading-tight text-white tracking-tight">{{ $opportunity->title }}</h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-3">{{ $opportunity->provider_name }} • {{ $opportunity->location }}</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Summary Callout -->
        <div class="p-8 bg-[#108010]/5 rounded-3xl border border-[#108010]/20 text-slate-800 text-sm sm:text-base leading-relaxed">
            <strong class="text-[#002040] block mb-2 font-black font-heading text-lg">Opportunity Summary</strong>
            {{ $opportunity->summary }}
        </div>

        <!-- Full Description -->
        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
            <h3 class="font-black text-xl text-[#002040] font-heading">Detailed Description & Scope</h3>
            {!! nl2br(e($opportunity->description)) !!}
        </div>

        <!-- Eligibility & Criteria -->
        @if ($opportunity->eligibility_criteria)
            <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm space-y-3">
                <h3 class="font-black text-lg text-[#002040] font-heading flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#108010]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Eligibility Criteria & Member Qualifications</span>
                </h3>
                <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $opportunity->eligibility_criteria }}</p>
            </div>
        @endif

        <!-- Action Box -->
        <div class="p-8 sm:p-10 bg-[#002040] rounded-3xl text-white shadow-2xl flex flex-col sm:flex-row justify-between items-center gap-6 border border-slate-800 relative overflow-hidden">
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10">
                <h3 class="text-xl sm:text-2xl font-black font-heading mb-1 text-white">How to Apply</h3>
                <p class="text-xs sm:text-sm text-slate-300">
                    @if ($opportunity->application_type === 'internal')
                        Direct intake through TEVDA Member Portal for active certified drivers.
                    @else
                        Official external partner application process.
                    @endif
                </p>
            </div>

            <div class="relative z-10 shrink-0">
                @if ($opportunity->application_type === 'internal')
                    <a href="{{ route('register') }}" class="bg-[#108010] hover:bg-[#0c660c] text-white font-bold px-8 py-3.5 rounded-xl text-xs sm:text-sm transition-all shadow-md shadow-[#108010]/30 cursor-pointer block text-center">
                        Apply via Portal
                    </a>
                @elseif ($opportunity->external_url)
                    <a href="{{ $opportunity->external_url }}" target="_blank" rel="noopener noreferrer" class="bg-[#E5B51B] hover:bg-[#cfa114] text-[#002040] font-black px-8 py-3.5 rounded-xl text-xs sm:text-sm transition-all shadow-md cursor-pointer block text-center">
                        External Application Link &rarr;
                    </a>
                @endif
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-between items-center">
            <a href="{{ route('opportunities') }}" class="text-xs font-bold text-[#108010] hover:text-[#002040] flex items-center gap-1 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to Opportunities</span>
            </a>
        </div>

    </div>
</div>
@endsection
