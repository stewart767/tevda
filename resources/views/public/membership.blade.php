@extends('layouts.app')

@section('title', 'Membership Categories, Eligibility & Registration — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-[#E5B51B] bg-[#001428]/90 px-3.5 py-1.5 rounded-full border border-[#108010]/60">Member Services</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Membership Categories & Benefits</h1>
        <p class="text-slate-200 text-sm sm:text-base max-w-2xl leading-relaxed">Discover qualifications, documentation requirements, and advantages of joining the official Tanzania Electric Vehicle Drivers Association.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Categories Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach ($categories as $cat)
                <div class="card-premium p-8 rounded-3xl border border-slate-200 flex flex-col justify-between hover:border-[#108010]/60 transition group bg-white">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-[#108010] bg-[#f2fbf2] px-3 py-1 rounded-full border border-[#108010]/30">
                                Tier {{ $cat->order_number }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium italic">{{ $cat->fee_status_note }}</span>
                        </div>

                        <h2 class="text-2xl font-black text-[#002040] font-heading mb-3">{{ $cat->name }}</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">{{ $cat->description }}</p>

                        <div class="space-y-4 mb-6">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                <strong class="text-xs font-extrabold text-[#002040] uppercase tracking-wider block mb-1">Eligibility Criteria</strong>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $cat->eligibility_criteria }}</p>
                            </div>

                            @if (!empty($cat->required_documents))
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                    <strong class="text-xs font-extrabold text-[#002040] uppercase tracking-wider block mb-2">Required Documentation</strong>
                                    <ul class="text-xs text-slate-600 space-y-1.5">
                                        @foreach ($cat->required_documents as $doc)
                                            <li class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-[#108010] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                <span>{{ $doc['label'] }} @if(!empty($doc['required'])) <strong class="text-rose-600">*</strong> @endif</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="text-xs text-slate-500 text-center sm:text-left">
                            <span>Registration Fee: </span>
                            <strong class="text-[#002040] font-black text-sm">{{ $cat->registration_fee > 0 ? number_format($cat->registration_fee) . ' TZS' : 'Configurable (TBD)' }}</strong>
                        </div>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto bg-[#108010] hover:bg-[#0b570b] text-white text-xs font-black px-6 py-3.5 rounded-xl transition text-center shadow-md shadow-[#108010]/20">
                            Apply for {{ $cat->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Benefits Summary Banner -->
        <div class="bg-[#002040] text-white rounded-3xl p-8 sm:p-12 shadow-xl border border-[#002c59] relative overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-3">
                    <span class="text-xs font-bold text-[#E5B51B] uppercase tracking-widest block font-mono">Why Join TEVDA</span>
                    <h3 class="text-2xl sm:text-3xl font-black font-heading text-white">Unlock National Representation, Certified Training & Financing</h3>
                    <p class="text-xs sm:text-sm text-slate-300 leading-relaxed">
                        TEVDA members receive digital QR-verifiable membership cards, certified technical training, priority access to commercial fleet projects, and legal driver defense.
                    </p>
                </div>
                <div class="lg:col-span-4 flex justify-start lg:justify-end">
                    <a href="{{ route('register') }}" class="bg-[#108010] hover:bg-[#0b570b] text-white font-extrabold px-8 py-4 rounded-xl text-xs sm:text-sm transition shadow-lg">
                        Become a Member Today
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
