@extends('layouts.app')

@section('title', $member ? 'Verified Member: ' . $member->full_name . ' (' . $member->membership_number . ') — TEVDA Official Registry' : 'Verify Membership Credentials — TEVDA Public Registry')

@section('content')
<div class="relative hero-pattern text-white py-12 lg:py-16 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/90 px-4 py-1.5 rounded-full border border-emerald-700/80 shadow-inner mb-3">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Official Public Verification Registry</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-2 mb-3 text-white tracking-tight">Membership Verification</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
            Real-time verification system for commercial electric vehicle drivers, institutional operators, and accredited TEVDA practitioners in Tanzania.
        </p>
    </div>
</div>

<div class="py-12 bg-slate-50/60 subtle-grid-pattern min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Search Box -->
        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/90 shadow-sm">
            <form action="{{ route('verify.membership') }}" method="GET" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Search Member or Card ID</label>
                    <a href="{{ route('track.application') }}" class="text-xs text-emerald-700 font-bold hover:underline flex items-center gap-1">
                        <span>Application Status & Payment Tracker &rarr;</span>
                    </a>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="query" value="{{ $searchQuery ?? '' }}" placeholder="Enter Membership No (TEVDA-2026-...), Card ID, Driving Licence, or Phone" required class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 font-mono transition">
                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white font-black px-8 py-3.5 rounded-xl text-xs sm:text-sm transition shadow-md shadow-emerald-600/20 shrink-0 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-qrcode"></i>
                        <span>Verify Credentials</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Result -->
        @if ($searchPerformed)
            @if ($member)
                <!-- Verification Success Card -->
                <div class="space-y-6">

                    <!-- Official Verification Banner -->
                    <div class="p-4 sm:p-5 rounded-3xl border-2 {{ $member->status === 'approved' ? 'bg-gradient-to-r from-emerald-900 via-emerald-950 to-slate-900 border-emerald-500 text-white shadow-xl shadow-emerald-950/20' : 'bg-amber-50 border-amber-400 text-amber-950' }} flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3.5 text-center sm:text-left">
                            <div class="w-12 h-12 rounded-2xl {{ $member->status === 'approved' ? 'bg-emerald-500 text-slate-950' : 'bg-amber-500 text-white' }} flex items-center justify-center text-xl shrink-0 shadow-md font-black">
                                @if ($member->status === 'approved')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @else
                                    <i class="fa-solid fa-clock"></i>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center justify-center sm:justify-start gap-2">
                                    <span class="text-[10px] uppercase font-black tracking-widest {{ $member->status === 'approved' ? 'text-emerald-300' : 'text-amber-800' }}">Official Security Audit</span>
                                    <span class="inline-block w-1.5 h-1.5 rounded-full {{ $member->status === 'approved' ? 'bg-emerald-400 animate-ping' : 'bg-amber-500' }}"></span>
                                </div>
                                <h2 class="text-lg sm:text-xl font-black font-heading tracking-tight {{ $member->status === 'approved' ? 'text-white' : 'text-amber-950' }}">
                                    {{ $member->status === 'approved' ? 'VERIFIED AUTHENTIC & ACTIVE MEMBER' : 'STATUS: ' . strtoupper($member->status) }}
                                </h2>
                                <p class="text-xs {{ $member->status === 'approved' ? 'text-emerald-200/80' : 'text-amber-800' }} mt-0.5">
                                    Member record matches the official registry database. Verified on {{ now()->format('d M Y, H:i') }} EAT.
                                </p>
                            </div>
                        </div>

                        @if ($member->status === 'approved')
                            <div class="shrink-0 flex items-center gap-2">
                                <a href="{{ route('public.card.download', $member->membership_number) }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition shadow-md">
                                    <i class="fa-solid fa-id-card"></i>
                                    <span>Download ID Card</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- SMART DIGITAL ID CARD PREVIEW (APPEARS PROMINENTLY ON SCAN) -->
                    @if ($member->status === 'approved')
                        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-lg space-y-6" x-data="{ isFlipped: false }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <h3 class="text-lg font-black text-slate-900 font-heading">Digital Membership ID Card</h3>
                                    </div>
                                    <p class="text-xs text-slate-500 mt-0.5">Scanned replica of the official physical and electronic credential.</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="isFlipped = false" :class="!isFlipped ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition">
                                        Front Side
                                    </button>
                                    <button type="button" @click="isFlipped = true" :class="isFlipped ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition">
                                        Back Side
                                    </button>
                                    <button type="button" @click="isFlipped = !isFlipped" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-800 text-white hover:bg-slate-700 transition flex items-center gap-1.5">
                                        <i class="fa-solid fa-arrows-rotate"></i>
                                        <span>Flip</span>
                                    </button>
                                </div>
                            </div>

                            <!-- 3D Card Display -->
                            <div class="max-w-md sm:max-w-lg mx-auto py-2" style="perspective: 1000px;">
                                <div class="w-full relative shadow-2xl rounded-2xl transition-transform duration-700 ease-in-out cursor-pointer"
                                    style="aspect-ratio: 85.6 / 53.98; transform-style: preserve-3d;"
                                    :style="isFlipped ? 'transform: rotateY(180deg)' : 'transform: rotateY(0deg)'"
                                    @click="isFlipped = !isFlipped"
                                    title="Click to flip ID Card">

                                    <!-- FRONT FACE -->
                                    <div class="absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white border-2 border-emerald-500/50 shadow-2xl"
                                        style="backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                                        
                                        <!-- National Flag Stripe -->
                                        <div class="h-1.5 w-full flex -mt-4 sm:-mt-5 -mx-4 sm:-mx-5 mb-2">
                                            <div class="w-1/3 bg-[#1eb53a]"></div>
                                            <div class="w-1/3 bg-[#fcd116]"></div>
                                            <div class="w-1/3 bg-[#00a3dd]"></div>
                                        </div>

                                        <!-- Header -->
                                        <div class="flex items-center justify-between pb-2 border-b border-white/10">
                                            <div class="flex items-center gap-2">
                                                @if(\App\Models\Setting::hasCustomLogo())
                                                    <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-7 sm:h-8 max-w-[40px] object-contain">
                                                @else
                                                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-lg bg-emerald-600 flex items-center justify-center font-bold text-white text-[10px] shadow-xs">TEV</div>
                                                @endif
                                                <div>
                                                    <span class="text-xs sm:text-sm font-black tracking-tight block font-heading leading-tight text-white">TEVDA</span>
                                                    <span class="text-[6.5px] sm:text-[7.5px] uppercase font-bold tracking-wider text-emerald-400 block">Tanzania Electric Vehicles Drivers Association</span>
                                                </div>
                                            </div>
                                            <span class="text-[7.5px] sm:text-[8.5px] uppercase font-black tracking-wider bg-emerald-900/90 text-emerald-300 px-2.5 py-0.5 rounded-md border border-emerald-500 shadow-xs">
                                                {{ $member->category->name }}
                                            </span>
                                        </div>

                                        <!-- Body -->
                                        <div class="grid grid-cols-12 gap-2.5 sm:gap-3 items-center my-auto">
                                            <!-- Passport Photo -->
                                            <div class="col-span-3">
                                                <div class="w-14 h-18 sm:w-20 sm:h-24 rounded-xl overflow-hidden border-2 border-emerald-500/70 shadow-lg bg-slate-900 flex items-center justify-center">
                                                    @if ($member->passport_photo_path)
                                                        <img src="{{ \App\Helpers\ImageHelper::getUrl($member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                                        <span class="hidden font-black text-lg text-emerald-400">{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                                                    @else
                                                        <div class="flex flex-col items-center justify-center text-slate-400 text-[8px] font-bold">
                                                            <svg class="w-6 h-6 mb-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                            <span>PHOTO</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Member Info -->
                                            <div class="col-span-6 space-y-1 sm:space-y-1.5 pl-1">
                                                <div>
                                                    <span class="text-[7px] sm:text-[8px] text-slate-400 block uppercase font-semibold leading-none">Member Name / Jina</span>
                                                    <strong class="text-white block font-black text-xs sm:text-sm truncate tracking-tight">{{ $member->full_name }}</strong>
                                                </div>
                                                <div>
                                                    <span class="text-[7px] sm:text-[8px] text-slate-400 block uppercase font-semibold leading-none">Member ID / Namba</span>
                                                    <span class="text-amber-400 font-mono font-black text-[11px] sm:text-xs inline-block px-1.5 py-0.5 rounded bg-emerald-950/90 border border-amber-500/70">{{ $member->membership_number }}</span>
                                                </div>
                                                <div>
                                                    <span class="text-[7px] sm:text-[8px] text-slate-400 block uppercase font-semibold leading-none">Region & Territory</span>
                                                    <span class="text-slate-200 font-semibold text-[9px] sm:text-[10px] block truncate">{{ $member->region?->name ?? 'Tanzania' }} @if($member->district) • {{ $member->district->name }} @endif</span>
                                                </div>
                                                <div>
                                                    <span class="text-[7px] sm:text-[8px] text-slate-400 block uppercase font-semibold leading-none">Licence & Validity</span>
                                                    <span class="text-[8.5px] sm:text-[10px] font-bold text-slate-200">
                                                        <span class="text-amber-400">{{ $member->expiry_date ? 'EXP: ' . $member->expiry_date->format('m/Y') : 'ACTIVE' }}</span>
                                                        <span class="text-emerald-400 ml-1 font-extrabold">• VERIFIED</span>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- QR Code -->
                                            <div class="col-span-3 flex flex-col items-end">
                                                @if (!empty($qrCodeUri))
                                                    <div class="bg-white p-1 rounded-xl shadow-md border-2 border-emerald-400">
                                                        <img src="{{ $qrCodeUri }}" alt="QR Code" class="w-12 h-12 sm:w-16 sm:h-16 object-contain">
                                                    </div>
                                                    <span class="text-[6px] sm:text-[7px] text-emerald-300 uppercase font-bold tracking-wider mt-1 text-center">Scan to Verify</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Micro Security Strip -->
                                        <div class="text-[6px] sm:text-[7px] text-center uppercase tracking-widest text-emerald-400/90 font-mono py-0.5 bg-black/40 -mx-4 sm:-mx-5 border-y border-white/5">
                                            • TANZANIA ELECTRIC VEHICLES DRIVERS ASSOCIATION • OFFICIAL SECURE SMART ID • TEVDA CERTIFIED •
                                        </div>

                                        <!-- Footer -->
                                        <div class="pt-1 border-t border-white/10 flex justify-between items-center text-[7px] sm:text-[8px]">
                                            <span class="tracking-wider text-amber-400 font-bold uppercase">SMART DRIVERS SMART MOBILITY</span>
                                            <span class="text-slate-400 uppercase font-mono font-bold">WWW.TEVDA.OR.TZ</span>
                                        </div>
                                    </div>

                                    <!-- BACK FACE -->
                                    <div class="absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white border-2 border-emerald-500/50 shadow-2xl"
                                        style="backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                                        <div class="h-7 bg-slate-950 -mx-4 sm:-mx-5 -mt-4 sm:-mt-5 px-4 sm:px-5 flex items-center justify-between border-b border-white/10">
                                            <span class="font-mono text-[8px] sm:text-[9px] text-slate-300 font-bold tracking-wider">CARD-{{ $member->membership_number }}</span>
                                            <span class="text-[7px] sm:text-[8px] text-emerald-400 font-bold uppercase tracking-wider">OFFICIAL SMART BADGE</span>
                                        </div>

                                        <div class="text-[7px] sm:text-[8px] leading-relaxed text-slate-300 text-left my-1">
                                            This official smart identification card certifies that the cardholder is a registered, compliant, and accredited member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations.
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 sm:gap-3 items-end">
                                            <div class="space-y-0.5">
                                                <span class="text-[6.5px] sm:text-[7.5px] uppercase text-slate-400 block font-semibold">Authorized Signatory</span>
                                                <div class="border-b border-slate-600 pb-0.5 min-h-[22px] flex items-center">
                                                    @if(\App\Models\Setting::hasChairmanSignature())
                                                        <img src="{{ \App\Models\Setting::getChairmanSignatureUrl() }}" alt="Signature" class="max-h-5 max-w-[85px] object-contain filter brightness-150">
                                                    @else
                                                        <span class="font-serif italic text-sm text-emerald-400">{{ \App\Models\Setting::get('chairman_name', 'Charles Mwansasu') }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-[7.5px] sm:text-[8.5px] font-bold block text-white">{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}</span>
                                                <span class="text-[6.5px] sm:text-[7.5px] text-slate-400 block">{{ \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }} • TEVDA</span>
                                            </div>

                                            <div class="p-2 rounded-xl bg-slate-900/90 text-[6.5px] sm:text-[7.5px] leading-tight space-y-0.5 border border-white/5 text-slate-300">
                                                <strong class="text-emerald-400 block font-bold">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                                                <p class="truncate">{{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}</p>
                                                <p class="font-mono font-bold text-amber-300">Helpline: {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}</p>
                                                <p class="truncate">Support: {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }}</p>
                                            </div>
                                        </div>

                                        <!-- Barcode -->
                                        <div class="text-center font-mono text-[8px] sm:text-[9px] text-slate-400 tracking-widest py-0.5">
                                            ||| | |||| | ||| |||| | || ||| |||| | || | |||
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-center text-[11px] text-slate-400 font-medium">
                                <i class="fa-solid fa-hand-pointer mr-1"></i> Click anywhere on the card or use buttons above to flip between Front and Back sides.
                            </p>
                        </div>
                    @endif

                    <!-- Member Registry Details -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-address-card text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 font-heading">Official Member Registry Details</h3>
                                <p class="text-xs text-slate-500">Verified identity parameters from TEVDA Database.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs sm:text-sm">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Full Registered Name</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $member->full_name }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Membership Number</span>
                                <strong class="text-emerald-700 font-mono text-sm font-black block mt-0.5">{{ $member->membership_number }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Membership Tier / Category</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $member->category->name }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Operating Region & District</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $member->region?->name ?? 'Tanzania' }} @if($member->district) • {{ $member->district->name }} @endif</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Licence & Driving Category</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $member->licence_class ? 'Class ' . $member->licence_class : 'Associate / Practitioner' }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Membership Validity Status</span>
                                <strong class="text-emerald-700 text-sm font-black block mt-0.5">
                                    {{ $member->expiry_date ? 'Valid until ' . $member->expiry_date->format('d M Y') : 'Active' }}
                                </strong>
                            </div>
                        </div>

                        <!-- Instant Action Buttons -->
                        @if ($member->status === 'approved')
                            <div class="p-5 bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-900 rounded-2xl border border-emerald-500/40 text-white space-y-3">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                    <div>
                                        <span class="text-[10px] uppercase font-black tracking-widest text-emerald-300">Verified Official Documents</span>
                                        <h4 class="text-sm font-black font-heading text-white">Instant Credential Download (No Login Required)</h4>
                                    </div>
                                    <span class="text-[10px] text-emerald-300 font-mono bg-emerald-900/60 px-2.5 py-1 rounded-full border border-emerald-600/50">Status: Authenticated</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5 pt-1">
                                    <a href="{{ route('public.card.download', $member->membership_number) }}" 
                                       class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-md">
                                        <i class="fa-solid fa-id-card"></i>
                                        <span>Download ID Card (PDF)</span>
                                    </a>

                                    <a href="{{ route('public.certificate.download', $member->membershipCertificate?->certificate_number ?? $member->membership_number) }}" 
                                       class="bg-cyan-600 hover:bg-cyan-500 text-white font-black px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-md">
                                        <i class="fa-solid fa-award"></i>
                                        <span>Download Certificate (PDF)</span>
                                    </a>

                                    <a href="{{ route('public.card.download', ['number' => $member->membership_number, 'format' => 'a4']) }}" 
                                       class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition">
                                        <i class="fa-solid fa-print text-emerald-400"></i>
                                        <span>Printable A4 Sheet</span>
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="p-4 bg-slate-950 rounded-2xl text-slate-300 text-xs flex flex-col sm:flex-row justify-between items-center gap-3">
                            <span class="text-emerald-400 font-bold tracking-wide">SMART DRIVERS SMART MOBILITY • TEVDA TANZANIA</span>
                            <a href="{{ route('track.application', ['query' => $member->membership_number ?: $member->phone]) }}" class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1.5">
                                <span>View Full Application & Payment Status</span>
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                </div>
            @else
                <div class="p-8 bg-rose-50 border-2 border-rose-200 rounded-3xl text-center text-rose-900 space-y-3 shadow-xs">
                    <svg class="w-12 h-12 text-rose-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="text-lg font-bold">No Verified Member Record Found</h3>
                    <p class="text-xs text-rose-700 max-w-md mx-auto">
                        No active membership record corresponds to "<strong>{{ $searchQuery ?? '' }}</strong>". Please confirm the membership number or check the applicant status tracker.
                    </p>
                    <div class="pt-2 flex justify-center gap-2">
                        <a href="{{ route('track.application', ['query' => $searchQuery ?? '']) }}" class="px-5 py-2.5 bg-rose-200 hover:bg-rose-300 text-rose-950 font-bold text-xs rounded-xl transition">
                            Track Application & Control Number
                        </a>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
