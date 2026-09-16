@extends('layouts.app')

@section('title', 'Membership Categories, Eligibility & Registration — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Member Services</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Membership Categories & Benefits</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Discover qualifications, documentation requirements, and advantages of joining the official Tanzania Electric Vehicle Drivers Association.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Categories Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach ($categories as $cat)
                <div class="glass-card p-8 rounded-3xl border border-slate-200/80 shadow-xs flex flex-col justify-between hover:border-emerald-500 transition group">
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200/60">
                                Tier {{ $cat->order_number }}
                            </span>
                            <span class="text-xs text-slate-500 font-medium italic">{{ $cat->fee_status_note }}</span>
                        </div>

                        <h2 class="text-2xl font-black text-slate-900 font-heading mb-3">{{ $cat->name }}</h2>
                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-6">{{ $cat->description }}</p>

                        <div class="space-y-4 mb-6">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                <strong class="text-xs font-extrabold text-slate-900 uppercase tracking-wider block mb-1">Eligibility Criteria</strong>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $cat->eligibility_criteria }}</p>
                            </div>

                            @if (!empty($cat->required_documents))
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                                    <strong class="text-xs font-extrabold text-slate-900 uppercase tracking-wider block mb-2">Required Documentation</strong>
                                    <ul class="text-xs text-slate-600 space-y-1.5">
                                        @foreach ($cat->required_documents as $doc)
                                            <li class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
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
                            <strong class="text-slate-900 font-black text-sm">{{ $cat->registration_fee > 0 ? number_format($cat->registration_fee) . ' TZS' : 'Configurable (TBD)' }}</strong>
                        </div>
                        <a href="{{ route('register') }}" class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-black px-6 py-3.5 rounded-xl transition text-center shadow-md shadow-emerald-600/20">
                            Apply for {{ $cat->name }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Membership Process Workflow -->
        <div class="bg-slate-950 text-white rounded-3xl p-8 sm:p-12 shadow-2xl border border-slate-800 relative overflow-hidden">
            <div class="text-center max-w-3xl mx-auto mb-12">
                <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3.5 py-1.5 rounded-xl border border-emerald-800">Registration Pathway</span>
                <h2 class="text-3xl font-black font-heading mt-3 text-white">Step-by-Step Membership Journey</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 text-center">
                <div class="p-6 bg-slate-900/90 rounded-2xl border border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-3 shadow-md shadow-emerald-600/30">1</div>
                    <h3 class="font-black text-sm text-white mb-1">Create Account</h3>
                    <p class="text-xs text-slate-400">Register with your name, phone, and chosen tier.</p>
                </div>

                <div class="p-6 bg-slate-900/90 rounded-2xl border border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-cyan-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-3 shadow-md shadow-cyan-600/30">2</div>
                    <h3 class="font-black text-sm text-white mb-1">Upload Documents</h3>
                    <p class="text-xs text-slate-400">Attach NIDA, Licence, and passport photo.</p>
                </div>

                <div class="p-6 bg-slate-900/90 rounded-2xl border border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-black text-sm flex items-center justify-center mx-auto mb-3 shadow-md shadow-indigo-600/30">3</div>
                    <h3 class="font-black text-sm text-white mb-1">Vetting & Review</h3>
                    <p class="text-xs text-slate-400">TEVDA Secretariat reviews credentials.</p>
                </div>

                <div class="p-6 bg-slate-900/90 rounded-2xl border border-slate-800">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500 text-slate-950 font-black text-sm flex items-center justify-center mx-auto mb-3 shadow-md shadow-emerald-500/30">4</div>
                    <h3 class="font-black text-sm text-white mb-1">Digital QR Card</h3>
                    <p class="text-xs text-slate-400">Receive verifiable QR ID & PDF Certificate.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
