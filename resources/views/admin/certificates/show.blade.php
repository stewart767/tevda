@extends('layouts.admin')

@section('title', 'Certificate: ' . $certificate->certificate_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.certificates.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $certificate->title }}</h1>
                    @if($certificate->status === 'valid')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">Valid</span>
                    @elseif($certificate->status === 'revoked')
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-100 text-rose-800 border border-rose-200">Revoked</span>
                    @endif
                </div>
                <p class="text-xs text-slate-500 font-mono mt-0.5">Certificate ID: {{ $certificate->certificate_number }}</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.certificates.download', $certificate->id) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-file-pdf"></i> Download Official PDF
            </a>

            @if($certificate->status === 'valid')
                <button type="button" onclick="document.getElementById('revokeModal').classList.remove('hidden')" class="px-3 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-ban"></i> Revoke
                </button>
            @endif

            <form method="POST" action="{{ route('admin.certificates.reissue', $certificate->id) }}" onsubmit="return confirm('Reissuing will replace this certificate with a fresh identifier. Continue?');">
                @csrf
                <button type="submit" class="px-3 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                    <i class="fa-solid fa-rotate"></i> Reissue
                </button>
            </form>
        </div>
    </div>

    <!-- Certificate Visual Showcase Container -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border-4 border-emerald-900/10 shadow-xl relative overflow-hidden text-center space-y-6">
        <!-- Watermark -->
        <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
            <i class="fa-solid fa-shield-halved text-[300px] text-emerald-900"></i>
        </div>

        <!-- Association Header & System Logo -->
        <div class="space-y-2 relative">
            @if(\App\Models\Setting::hasCustomLogo())
                <div class="flex justify-center mb-2">
                    <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Logo" class="h-16 max-w-xs object-contain filter drop-shadow-sm">
                </div>
            @endif
            <div class="inline-block px-3.5 py-1 bg-emerald-50 text-emerald-800 text-xs font-black tracking-widest uppercase rounded-full border border-emerald-200">
                Tanzania Electric Vehicle Drivers Association
            </div>
            <p class="text-[11px] font-bold text-amber-600 tracking-wider">SMART DRIVERS SMART MOBILITY</p>
        </div>

        <!-- Title -->
        <div class="space-y-2 relative">
            <h2 class="text-3xl sm:text-4xl font-black text-slate-900 font-serif italic">{{ $certificate->title }}</h2>
            <p class="text-xs text-slate-500 uppercase tracking-widest">This is officially presented to</p>
            <h3 class="text-2xl sm:text-3xl font-black text-emerald-800 underline decoration-amber-400 decoration-4 underline-offset-8">{{ $certificate->recipient_name }}</h3>
        </div>

        <!-- Description / Achievement -->
        <div class="max-w-xl mx-auto text-xs text-slate-600 leading-relaxed relative">
            In recognition of meeting all constitutional requirements, driver qualifications, safety standards and curriculum benchmarks as prescribed under TEVDA governance framework.
            @if($certificate->course_name)
                <div class="mt-2 font-bold text-slate-800">Specialization / Course: {{ $certificate->course_name }}</div>
            @endif
            @if($certificate->grade)
                <div class="text-emerald-700 font-bold">Graded Result: {{ $certificate->grade }}</div>
            @endif
        </div>

        <!-- Signatures & Pure PHP Vector QR Code -->
        <div class="pt-8 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 items-center gap-6 text-left relative">
            <div class="text-center sm:text-left space-y-1">
                <div class="h-10 flex items-end justify-center sm:justify-start">
                    @if($certificate->getSignatureUrl())
                        <img src="{{ $certificate->getSignatureUrl() }}" alt="Signature" class="max-h-10 max-w-[170px] object-contain border-b-2 border-slate-300 pb-1">
                    @else
                        <span class="font-serif italic font-bold text-slate-800 border-b-2 border-slate-300 pb-1 px-4">{{ $certificate->authorized_person_name ?? \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}</span>
                    @endif
                </div>
                <div class="text-[11px] font-bold text-slate-700">{{ $certificate->authorized_person_title ?? \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }}</div>
                <div class="text-[10px] text-slate-400">TEVDA Executive Council</div>
            </div>

            <!-- Vector QR Code with Direct SVG Rendering -->
            <div class="flex flex-col items-center justify-center space-y-1">
                <div class="p-2 bg-white rounded-xl border border-slate-200 shadow-sm inline-block">
                    {!! $qrSvg !!}
                </div>
                <a href="{{ $verifyUrl }}" target="_blank" class="text-[10px] text-emerald-600 hover:underline font-mono">
                    Scan or Click to Verify
                </a>
            </div>

            <div class="text-center sm:text-right space-y-1 text-xs">
                <div><span class="text-slate-400">Issue Date:</span> <strong class="text-slate-800">{{ $certificate->issue_date ? $certificate->issue_date->format('d M Y') : 'N/A' }}</strong></div>
                @if($certificate->expiry_date)
                    <div><span class="text-slate-400">Expiry Date:</span> <strong class="text-slate-800">{{ $certificate->expiry_date->format('d M Y') }}</strong></div>
                @endif
                <div><span class="text-slate-400">Registry ID:</span> <strong class="font-mono text-[11px] text-slate-700">{{ $certificate->certificate_number }}</strong></div>
            </div>
        </div>
    </div>

    @if($certificate->status === 'revoked')
        <div class="bg-rose-50 border border-rose-200 rounded-2xl p-5 text-xs text-rose-900 space-y-1">
            <div class="font-bold flex items-center gap-1.5 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation"></i> Revocation Notice
            </div>
            <p><strong>Reason:</strong> {{ $certificate->revocation_reason }}</p>
            <p class="text-slate-500">Revoked by {{ $certificate->revoker?->name ?? 'Administrator' }} on {{ $certificate->revoked_at?->format('d M Y, H:i') }}</p>
        </div>
    @endif
</div>

<!-- Modal: Revoke Certificate -->
<div id="revokeModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-ban text-rose-600"></i> Revoke Certificate
            </h3>
            <button type="button" onclick="document.getElementById('revokeModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p class="text-xs text-slate-500">
            Revoking will immediately mark this certificate as invalid in the public verification system.
        </p>
        <form method="POST" action="{{ route('admin.certificates.revoke', $certificate->id) }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Reason for Revocation *</label>
                <textarea name="reason" rows="3" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-rose-500" placeholder="Licence cancelled or fraud detected..."></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" onclick="document.getElementById('revokeModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold">
                    Confirm Revocation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
