@extends('layouts.admin')

@section('title', 'Member ID Card — ' . $member->full_name)

@push('styles')
<style>
    .perspective-container {
        perspective: 1200px;
    }
    .card-flip-inner {
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        transform-style: preserve-3d;
    }
    .card-flip-inner.is-flipped {
        transform: rotateY(180deg);
    }
    .card-face {
        backface-visibility: hidden;
        -webkit-backface-visibility: hidden;
    }
    .card-face-back {
        transform: rotateY(180deg);
    }
    .id-card-preview {
        width: 100%;
        max-width: 440px;
        aspect-ratio: 85.6 / 53.98;
        border-radius: 16px;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="{ isFlipped: false }">

    <!-- Top Navigation Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.cards.index') }}" class="hover:text-emerald-600 transition">ID Cards</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">{{ $member->full_name }} ({{ $card->card_number }})</span>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.cards.download', ['id' => $card->id, 'format' => 'cr80']) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-3.5 py-2 rounded-xl shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <span>Download CR80 PDF</span>
            </a>

            <a href="{{ route('admin.cards.download', ['id' => $card->id, 'format' => 'a4']) }}" class="inline-flex items-center gap-1.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs px-3.5 py-2 rounded-xl transition">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span>A4 Sheet PDF</span>
            </a>

            <a href="{{ route('admin.cards.print', $card->id) }}" target="_blank" class="inline-flex items-center gap-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Print Card</span>
            </a>
        </div>
    </div>

    <!-- Main Grid: Card 3D Viewer on Left, Details & Actions on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: 3D Interactive Card (5 cols) -->
        <div class="lg:col-span-5 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold font-heading text-slate-900">Official CR80 ID Card</h2>
                        <span class="text-[10px] text-slate-400">Click card or button below to flip</span>
                    </div>
                    <button type="button" @click="isFlipped = !isFlipped" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span x-text="isFlipped ? 'View Front Side' : 'Flip to Back Side'"></span>
                    </button>
                </div>

                @php
                    $theme = $card->card_data['theme'] ?? 'emerald';
                @endphp

                <!-- 3D Card Container -->
                <div class="perspective-container py-2 flex justify-center">
                    <div class="id-card-preview card-flip-inner relative shadow-2xl cursor-pointer" :class="{ 'is-flipped': isFlipped }" @click="isFlipped = !isFlipped" title="Click to flip card">
                        
                        <!-- ==================== FRONT SIDE ==================== -->
                        <div class="card-face absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden border
                            {{ $theme === 'clean' ? 'bg-gradient-to-br from-slate-50 via-white to-slate-100 text-slate-900 border-slate-300' : ($theme === 'midnight' ? 'bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 text-white border-amber-500/40' : ($theme === 'cyan' ? 'bg-gradient-to-br from-slate-950 via-cyan-950 to-teal-950 text-white border-cyan-500/40' : 'bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white border-emerald-500/40')) }}">
                            
                            <!-- Header -->
                            <div class="flex items-center justify-between pb-2 border-b {{ $theme === 'clean' ? 'border-slate-200' : 'border-white/10' }}">
                                <div class="flex items-center gap-2">
                                    @if(\App\Models\Setting::hasCustomLogo())
                                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-6 max-w-[32px] object-contain">
                                    @else
                                        <div class="w-6 h-6 rounded-md bg-emerald-600 text-white font-black text-[9px] flex items-center justify-center">TEV</div>
                                    @endif
                                    <div>
                                        <span class="text-[11px] font-black tracking-tight block font-heading leading-tight {{ $theme === 'clean' ? 'text-emerald-800' : 'text-white' }}">TEVDA</span>
                                        <span class="text-[6.5px] uppercase font-bold tracking-wider block {{ $theme === 'midnight' ? 'text-amber-400' : ($theme === 'cyan' ? 'text-cyan-400' : ($theme === 'clean' ? 'text-emerald-600' : 'text-emerald-400')) }}">Tanzania EV Drivers Association</span>
                                    </div>
                                </div>
                                <span class="text-[7.5px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border
                                    {{ $theme === 'clean' ? 'bg-slate-200 text-slate-800 border-slate-300' : ($theme === 'midnight' ? 'bg-amber-900/70 text-amber-300 border-amber-700' : ($theme === 'cyan' ? 'bg-cyan-900/70 text-cyan-300 border-cyan-700' : 'bg-emerald-900/70 text-emerald-300 border-emerald-700')) }}">
                                    {{ $member->category->name }}
                                </span>
                            </div>

                            <!-- Body -->
                            <div class="grid grid-cols-12 gap-3 items-center my-auto">
                                <div class="col-span-3">
                                    @if($member->passport_photo_path)
                                        <img src="{{ asset('storage/' . $member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-14 h-18 sm:w-16 sm:h-20 object-cover rounded-xl border-2 shadow-xs {{ $theme === 'clean' ? 'border-emerald-600' : 'border-emerald-500/50' }}">
                                    @else
                                        <div class="w-14 h-18 sm:w-16 sm:h-20 rounded-xl border flex flex-col items-center justify-center text-[8px] font-bold {{ $theme === 'clean' ? 'bg-slate-200 border-slate-300 text-slate-500' : 'bg-slate-800/80 border-slate-700 text-slate-400' }}">
                                            <svg class="w-5 h-5 mb-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>PHOTO</span>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-span-6 space-y-1">
                                    <div>
                                        <span class="text-[7.5px] uppercase text-slate-400 block font-semibold leading-none">Member Name</span>
                                        <strong class="text-xs sm:text-sm font-black block truncate {{ $theme === 'clean' ? 'text-slate-900' : 'text-white' }}">{{ $member->full_name }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] uppercase text-slate-400 block font-semibold leading-none">Member Number</span>
                                        <span class="font-mono font-bold text-xs block {{ $theme === 'midnight' ? 'text-amber-400' : ($theme === 'cyan' ? 'text-cyan-400' : ($theme === 'clean' ? 'text-emerald-700' : 'text-emerald-400')) }}">{{ $member->membership_number }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] uppercase text-slate-400 block font-semibold leading-none">Region / Valid Thru</span>
                                        <span class="text-[10px] font-semibold block {{ $theme === 'clean' ? 'text-slate-600' : 'text-slate-300' }}">
                                            {{ $member->region?->name ?? 'Tanzania' }} • <strong class="text-amber-400">{{ $card->expiry_date ? $card->expiry_date->format('m/Y') : 'ACTIVE' }}</strong>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-span-3 flex flex-col items-end">
                                    <div class="bg-white p-1 rounded-lg shadow-xs">
                                        <div class="w-10 h-10 sm:w-11 sm:h-11">
                                            {!! $qrSvg !!}
                                        </div>
                                    </div>
                                    <span class="text-[5.5px] uppercase tracking-wider text-slate-400 mt-1">Scan to Verify</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="pt-2 border-t flex items-center justify-between {{ $theme === 'clean' ? 'border-slate-200' : 'border-white/10' }}">
                                <span class="text-[7.5px] font-bold uppercase tracking-wider {{ $theme === 'midnight' ? 'text-amber-400' : ($theme === 'cyan' ? 'text-cyan-400' : ($theme === 'clean' ? 'text-emerald-800' : 'text-emerald-400')) }}">
                                    {{ $card->card_data['motto'] ?? 'SMART DRIVERS SMART MOBILITY' }}
                                </span>
                                <span class="text-[7.5px] text-slate-400 font-bold uppercase">WWW.TEVDA.OR.TZ</span>
                            </div>
                        </div>

                        <!-- ==================== BACK SIDE ==================== -->
                        <div class="card-face card-face-back absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden border
                            {{ $theme === 'clean' ? 'bg-gradient-to-br from-slate-50 via-white to-slate-100 text-slate-900 border-slate-300' : ($theme === 'midnight' ? 'bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 text-white border-amber-500/40' : ($theme === 'cyan' ? 'bg-gradient-to-br from-slate-950 via-cyan-950 to-teal-950 text-white border-cyan-500/40' : 'bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white border-emerald-500/40')) }}">
                            
                            <div class="h-6 bg-slate-950 -mx-5 -mt-5 px-5 flex items-center justify-between border-b border-white/5">
                                <span class="font-mono text-[7.5px] text-slate-400 tracking-wider">{{ $card->card_number }}</span>
                                <span class="text-[6.5px] text-emerald-400 font-bold uppercase">TEVDA DIGITAL BADGE</span>
                            </div>

                            <div class="text-[7.5px] leading-tight text-slate-400 text-justify my-1">
                                This official card certifies authorized membership in TEVDA. Non-transferable and must be presented upon inspection. Subject to association bylaws.
                            </div>

                            <div class="grid grid-cols-2 gap-3 items-end">
                                <div class="space-y-0.5">
                                    <span class="text-[6.5px] uppercase text-slate-400 block">Authorized Signature</span>
                                    <div class="border-b border-slate-600 pb-0.5">
                                        <span class="font-serif italic text-xs text-emerald-400">Charles Mwansasu</span>
                                    </div>
                                    <span class="text-[7px] font-bold block {{ $theme === 'clean' ? 'text-slate-900' : 'text-white' }}">Dr. Charles Mwansasu</span>
                                    <span class="text-[6.5px] text-slate-400 block">Founding Chairperson • TEVDA</span>
                                </div>

                                <div class="p-2 rounded-xl text-[7px] leading-tight space-y-0.5 {{ $theme === 'clean' ? 'bg-slate-200/80 text-slate-700' : 'bg-slate-900/90 text-slate-300 border border-white/5' }}">
                                    <strong class="text-emerald-400 block font-bold text-[7.5px]">TEVDA HQ & HELPLINE</strong>
                                    <p>Dar es Salaam, Tanzania</p>
                                    <p class="font-mono">+255 700 000 000</p>
                                    <p>info@tevda.or.tz</p>
                                </div>
                            </div>

                            <div class="text-[6.5px] text-center text-slate-500 uppercase tracking-wider pt-1 border-t border-white/5">
                                Property of TEVDA. If found, return to nearest TEVDA branch.
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Card Status & Renewal Actions -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] text-slate-400 uppercase font-semibold block">Card Status</span>
                        @if(!$card->is_active)
                            <span class="text-xs font-bold text-slate-500">Deactivated</span>
                        @elseif($card->expiry_date && $card->expiry_date->isPast())
                            <span class="text-xs font-bold text-rose-600">Expired</span>
                        @else
                            <span class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                Active & Valid
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.cards.toggle_status', $card->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-xl border transition {{ $card->is_active ? 'border-slate-300 text-slate-700 hover:bg-slate-100' : 'border-emerald-300 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">
                                {{ $card->is_active ? 'Deactivate Card' : 'Activate Card' }}
                            </button>
                        </form>

                        <form action="{{ route('admin.cards.reissue', $card->id) }}" method="POST" onsubmit="return confirm('Renew this ID card with 1-year extension?');">
                            @csrf
                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 transition">
                                Renew Card (1 Year)
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Member Details & Verification QR (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Member Overview Card -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <h3 class="text-sm font-bold font-heading text-slate-900">Member Registry Profile</h3>
                    <a href="{{ route('admin.members.show', $member->id) }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition">View Full Member Profile &rarr;</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Full Name</span>
                        <strong class="text-slate-900 text-sm font-bold">{{ $member->full_name }}</strong>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Membership Number</span>
                        <span class="text-emerald-600 font-mono font-bold">{{ $member->membership_number }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Membership Category</span>
                        <span class="font-semibold text-slate-800">{{ $member->category->name }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Phone Number</span>
                        <span class="text-slate-800 font-mono">{{ $member->phone }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Email Address</span>
                        <span class="text-slate-800">{{ $member->email }}</span>
                    </div>

                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Region & District</span>
                        <span class="text-slate-800">{{ $member->region?->name ?? 'Tanzania' }} @if($member->district) / {{ $member->district->name }} @endif</span>
                    </div>

                    @if($member->nida_number)
                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">NIDA Number</span>
                        <span class="text-slate-800 font-mono">{{ $member->nida_number }}</span>
                    </div>
                    @endif

                    @if($member->driving_licence_number)
                    <div>
                        <span class="text-[10px] uppercase text-slate-400 block font-semibold">Driving Licence</span>
                        <span class="text-slate-800 font-mono">{{ $member->driving_licence_number }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Public Verification Testing Widget -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                    <div>
                        <h3 class="text-sm font-bold font-heading text-slate-900">Live Verification Endpoint</h3>
                        <p class="text-[11px] text-slate-400">Scanned by police, transport regulators, or association officers</p>
                    </div>
                    <span class="text-[10px] font-bold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full">QR CONNECTED</span>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                    <div class="bg-white p-2 rounded-xl shadow-xs shrink-0">
                        <div class="w-16 h-16">
                            {!! $qrSvg !!}
                        </div>
                    </div>

                    <div class="space-y-1.5 text-xs">
                        <div class="font-semibold text-slate-700">Verification URL:</div>
                        <div class="p-2 bg-white rounded-lg border border-slate-200 font-mono text-[11px] text-slate-600 break-all select-all">
                            {{ $verifyUrl }}
                        </div>
                        <a href="{{ $verifyUrl }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 hover:text-emerald-700">
                            <span>Test Public Verification Screen</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection
