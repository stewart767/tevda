@extends('layouts.app')

@section('title', 'Verify Membership Credentials — TEVDA Public Registry')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Official Public Registry</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-2 text-white">Membership Verification</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">Verify the active membership status and credentials of commercial electric vehicle drivers and institutional members.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Search Box -->
        <div class="glass-card p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs">
            <form action="{{ route('verify.membership') }}" method="GET" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">Search Member or Application</label>
                    <a href="{{ route('track.application') }}" class="text-xs text-emerald-700 font-bold hover:underline flex items-center gap-1">
                        <span>Application Status & Payment Tracker &rarr;</span>
                    </a>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="query" value="{{ $searchQuery ?? '' }}" placeholder="Membership No, Driving Licence (DL-XXXX), or Phone (0757...)" required class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 font-mono transition">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-black px-8 py-3.5 rounded-xl text-xs sm:text-sm transition shadow-md shadow-emerald-600/20">
                        Verify Member
                    </button>
                </div>
            </form>
        </div>

        <!-- Result -->
        @if ($searchPerformed)
            @if ($member)
                <div class="bg-white p-8 rounded-3xl border-2 {{ $member->status === 'approved' ? 'border-emerald-500 bg-emerald-50/15' : 'border-slate-300' }} shadow-xl space-y-6 relative overflow-hidden">
                    <!-- Hologram Watermark Background -->
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-emerald-500/5 rounded-full pointer-events-none"></div>

                    <!-- Status Header -->
                    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-6 border-b border-slate-200 relative z-10">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-2xl bg-emerald-700 text-white flex items-center justify-center font-black text-2xl shadow-md border-2 border-emerald-500/50 shrink-0 overflow-hidden">
                                @if ($member->passport_photo_path)
                                    <img src="{{ \App\Helpers\ImageHelper::getUrl($member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <span class="hidden">{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                                @else
                                    <span>{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 font-bold uppercase tracking-wider block">Official Member Record</span>
                                <h2 class="text-2xl font-black text-slate-900 font-heading leading-tight">{{ $member->full_name }}</h2>
                                <p class="text-xs font-mono text-emerald-700 font-black mt-0.5">{{ $member->membership_number }}</p>
                            </div>
                        </div>

                        <div>
                            @if ($member->status === 'approved')
                                <span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-black px-4 py-2 rounded-xl uppercase tracking-wider shadow-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    VERIFIED ACTIVE MEMBER
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-amber-500 text-slate-950 text-xs font-black px-4 py-2 rounded-xl uppercase tracking-wider">
                                    STATUS: {{ strtoupper($member->status) }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Verified Details Table -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs sm:text-sm text-slate-700 relative z-10">
                        <div class="p-4 bg-white/90 rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Membership Tier</span>
                            <strong class="text-slate-900 text-sm font-bold">{{ $member->category->name }}</strong>
                        </div>

                        <div class="p-4 bg-white/90 rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Operating Region</span>
                            <strong class="text-slate-900 text-sm font-bold">{{ $member->region?->name ?? 'Tanzania' }}</strong>
                        </div>

                        <div class="p-4 bg-white/90 rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Licence & Driving Category</span>
                            <strong class="text-slate-900 text-sm font-bold">{{ $member->licence_class ? 'Class ' . $member->licence_class : 'Associate / Industry Practitioner' }}</strong>
                        </div>

                        <div class="p-4 bg-white/90 rounded-2xl border border-slate-200">
                            <span class="text-[11px] text-slate-400 font-semibold uppercase block">Membership Validity</span>
                            <strong class="text-slate-900 text-sm font-bold">{{ $member->expiry_date ? 'Valid until ' . $member->expiry_date->format('d M Y') : 'Active' }}</strong>
                        </div>
                    </div>

                    <!-- Download Credentials Panel (If Approved) -->
                    @if ($member->status === 'approved')
                        <div class="p-5 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 rounded-2xl border border-emerald-500/40 text-white space-y-3 relative z-10">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                <div>
                                    <span class="text-[10px] uppercase font-black tracking-widest text-emerald-300">Verified Member Credentials</span>
                                    <h3 class="text-sm font-black font-heading text-white">Instant Credential Download (No Login Required)</h3>
                                </div>
                                <span class="text-[10px] text-emerald-300 font-mono">Status: Authenticated</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                                <a href="{{ route('public.card.download', $member->membership_number) }}" 
                                   class="bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    <span>Download ID Card (PDF)</span>
                                </a>

                                <a href="{{ route('public.certificate.download', $member->membershipCertificate?->certificate_number ?? $member->membership_number) }}" 
                                   class="bg-cyan-600 hover:bg-cyan-500 text-white font-black px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-md">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    <span>Download Certificate (PDF)</span>
                                </a>

                                <a href="{{ route('public.card.download', ['number' => $member->membership_number, 'format' => 'a4']) }}" 
                                   class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition">
                                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Printable A4 ID Sheet</span>
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="p-4 bg-slate-950 rounded-2xl text-slate-300 text-xs flex flex-col sm:flex-row justify-between items-center gap-3 relative z-10">
                        <span class="text-emerald-400 font-bold">SMART DRIVERS SMART MOBILITY</span>
                        <a href="{{ route('track.application', ['query' => $member->membership_number ?: $member->phone]) }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition">
                            View Full Application & Payment Status &rarr;
                        </a>
                    </div>
                </div>
            @else
                <div class="p-8 bg-rose-50 border-2 border-rose-200 rounded-3xl text-center text-rose-900 space-y-3">
                    <svg class="w-10 h-10 text-rose-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="text-lg font-bold">No Verified Member Record Found</h3>
                    <p class="text-xs text-rose-700 max-w-md mx-auto">
                        No active membership record corresponds to "<strong>{{ $searchQuery ?? '' }}</strong>". Please confirm the details or check the application status.
                    </p>
                    <div class="pt-2 flex justify-center gap-2">
                        <a href="{{ route('track.application', ['query' => $searchQuery ?? '']) }}" class="px-4 py-2 bg-rose-200 hover:bg-rose-300 text-rose-950 font-bold text-xs rounded-xl transition">
                            Track Application & Control Number
                        </a>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
