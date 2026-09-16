@extends('layouts.app')

@section('title', 'Verify Certificate Authenticity — TEVDA Public Registry')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Official Certificate Registry</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-2 text-white">Certificate Verification</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">Verify the authenticity, issue dates, and active validity of TEVDA Membership, Training Completion, and Professional Competency certificates.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Search Box -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <form action="{{ route('verify.certificate') }}" method="GET" class="space-y-3">
                <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">Search By Certificate Number</label>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="number" value="{{ $certificateNumber }}" placeholder="e.g. TEVDA-CERT-2026-000001" required class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-cyan-500 font-mono transition">
                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-black px-8 py-3.5 rounded-xl text-xs sm:text-sm transition shadow-md shadow-cyan-600/20">
                        Verify Certificate
                    </button>
                </div>
            </form>
        </div>

        <!-- Result -->
        @if ($searchPerformed)
            @if ($certificate)
                <div class="bg-white p-8 rounded-3xl border-2 {{ $certificate->status === 'valid' ? 'border-emerald-500 bg-emerald-50/15' : ($certificate->status === 'revoked' ? 'border-rose-500 bg-rose-50/20' : 'border-amber-500 bg-amber-50/20') }} shadow-xl space-y-6 relative overflow-hidden">
                    <!-- Status Header -->
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-6 border-b border-slate-200">
                        <div class="flex items-start gap-4">
                            @if(\App\Models\Setting::hasCustomLogo())
                                <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-12 max-w-[120px] object-contain hidden sm:block">
                            @endif
                            <div>
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Official Certificate Record</span>
                                <h2 class="text-2xl font-black text-slate-900 font-heading">{{ $certificate->title }}</h2>
                                <p class="text-xs font-mono text-cyan-700 font-black mt-0.5">{{ $certificate->certificate_number }}</p>
                            </div>
                        </div>

                        <div>
                            @if ($certificate->status === 'valid')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-black px-4 py-2 rounded-xl uppercase tracking-wider shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    VERIFIED AUTHENTIC CERTIFICATE
                                </span>
                            @elseif ($certificate->status === 'revoked')
                                <span class="inline-flex items-center gap-1.5 bg-rose-600 text-white text-xs font-black px-4 py-2 rounded-xl uppercase tracking-wider shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    REVOKED CERTIFICATE
                                </span>
                            @elseif ($certificate->status === 'replaced')
                                <span class="inline-flex items-center gap-1.5 bg-slate-700 text-white text-xs font-black px-4 py-2 rounded-xl uppercase tracking-wider shadow-sm">
                                    REPLACED BY NEW CERTIFICATE
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-amber-500 text-slate-950 text-xs font-black px-4 py-2 rounded-xl uppercase tracking-wider">
                                    STATUS: {{ strtoupper($certificate->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Certificate Metadata Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm text-slate-700">
                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Recipient Name</span>
                            <strong class="text-slate-900 text-base font-heading">{{ $certificate->recipient_name }}</strong>
                        </div>

                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Certificate Type</span>
                            <strong class="text-slate-900 text-sm">{{ ucwords(str_replace('_', ' ', $certificate->certificate_type)) }}</strong>
                        </div>

                        @if ($certificate->course_name)
                            <div class="p-4 bg-white rounded-2xl border border-slate-200 sm:col-span-2">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Course / Programme</span>
                                <strong class="text-slate-900 text-sm">{{ $certificate->course_name }}</strong>
                            </div>
                        @endif

                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Issue Date</span>
                            <strong class="text-slate-900 text-sm">{{ $certificate->issue_date->format('d F Y') }}</strong>
                        </div>

                        <div class="p-4 bg-white rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Authorized Authority</span>
                            <strong class="text-slate-900 text-sm">{{ $certificate->authorized_person_name }}</strong>
                            <span class="text-[11px] text-slate-500 block">{{ $certificate->authorized_person_title }}</span>
                        </div>
                    </div>

                    @if ($certificate->status === 'revoked' && $certificate->revocation_reason)
                        <div class="p-4 bg-rose-100 border border-rose-300 rounded-2xl text-xs text-rose-900">
                            <strong>Official Revocation Reason:</strong> {{ $certificate->revocation_reason }}
                        </div>
                    @endif

                    <div class="p-4 bg-slate-950 rounded-2xl text-slate-300 text-xs flex justify-between items-center">
                        <span class="text-emerald-400 font-bold">SMART DRIVERS SMART MOBILITY</span>
                        <span class="text-slate-400 text-[11px]">Tanzania Electric Vehicle Drivers Association</span>
                    </div>
                </div>
            @else
                <div class="p-8 bg-rose-50 border-2 border-rose-200 rounded-3xl text-center text-rose-900 space-y-2">
                    <svg class="w-10 h-10 text-rose-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="text-lg font-bold">Invalid or Unrecognized Certificate Number</h3>
                    <p class="text-xs text-rose-700 max-w-md mx-auto">
                        No certificate record found matching "<strong>{{ $certificateNumber }}</strong>". Please verify the code printed on the physical or PDF certificate.
                    </p>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
