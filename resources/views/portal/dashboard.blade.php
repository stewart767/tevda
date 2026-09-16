@extends('layouts.portal')

@section('title', 'Member Dashboard — TEVDA')

@section('content')
<div class="space-y-8">
    
    <!-- Top Welcome Banner -->
    <div class="bg-gradient-to-r from-slate-900 via-emerald-950 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-xl relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
            <div class="lg:col-span-8 space-y-2">
                <div class="flex items-center gap-2">
                    <span class="text-[10px] uppercase font-bold text-emerald-400 bg-emerald-900/60 border border-emerald-700/50 px-2.5 py-0.5 rounded-full">
                        {{ $member->category->name }}
                    </span>
                    @if ($member->status === 'approved')
                        <span class="text-[10px] font-bold bg-emerald-500 text-slate-950 px-2.5 py-0.5 rounded-full flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            ACTIVE MEMBER
                        </span>
                    @else
                        <span class="text-[10px] font-bold bg-amber-400 text-slate-950 px-2.5 py-0.5 rounded-full">
                            STATUS: {{ strtoupper(str_replace('_', ' ', $member->status)) }}
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-black font-heading tracking-tight">Welcome, {{ $member->full_name }}</h1>
                <p class="text-xs sm:text-sm text-slate-300">
                    Membership Number: <strong class="text-emerald-400 font-mono">{{ $member->membership_number ?? 'Pending Approval' }}</strong>
                    • Region: <strong>{{ $member->region?->name ?? 'Tanzania' }}</strong>
                </p>
            </div>

            <!-- Quick Action Buttons -->
            <div class="lg:col-span-4 flex flex-wrap gap-3 justify-start lg:justify-end">
                @if ($member->status === 'approved')
                    <a href="{{ route('portal.card.download') }}" class="inline-flex items-center gap-1.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 text-xs font-bold px-4 py-2.5 rounded-xl transition shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download ID Card</span>
                    </a>
                    @if ($member->membershipCertificate)
                        <a href="{{ route('portal.certificate.download', $member->membershipCertificate->id) }}" class="inline-flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Certificate PDF</span>
                        </a>
                    @endif
                @else
                    <div class="p-3 bg-slate-800/80 rounded-xl border border-slate-700 text-[11px] text-slate-300">
                        Your application is undergoing verification. ID Card & Certificate will be unlocked upon fee payment and approval.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Control Number Box (When Unpaid) -->
    @if ($latestInvoice && $latestInvoice->status !== 'paid')
        <div class="bg-gradient-to-r from-slate-900 via-slate-950 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white border-2 border-emerald-500/50 shadow-2xl space-y-6" x-data="{ copied: false }">
            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-4 border-b border-white/10">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3 py-1 rounded-md border border-emerald-700">
                        Electronic Payment Required
                    </span>
                    <h2 class="text-xl sm:text-2xl font-black font-heading text-white mt-1">
                        Membership Fee Payment & Certificate Issuance
                    </h2>
                    <p class="text-xs text-slate-300">
                        In order to receive and print your official <strong>Certificate of Membership</strong> and Smart ID Card, please pay using your Control Number below.
                    </p>
                </div>
                <div class="bg-emerald-900/50 p-3.5 rounded-2xl border border-emerald-500/40 text-left sm:text-right shrink-0">
                    <span class="text-[10px] uppercase font-bold text-emerald-300 block">Amount to Pay</span>
                    <strong class="text-2xl sm:text-3xl font-black text-emerald-400 font-mono">
                        {{ number_format($latestInvoice->amount) }} {{ $latestInvoice->currency }}
                    </strong>
                </div>
            </div>

            <!-- Big Control Number Box -->
            <div class="p-5 bg-slate-900/90 rounded-2xl border border-emerald-500/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs uppercase font-extrabold text-slate-400 tracking-wider block">Official Payment Control Number</span>
                    <div class="text-3xl sm:text-4xl font-black font-mono tracking-wider text-emerald-400 mt-1">
                        {{ $latestInvoice->control_number }}
                    </div>
                    <span class="text-[11px] text-slate-400">Pay via M-Pesa, Tigo Pesa, Airtel Money, Halopesa, or NMB/CRDB Bank</span>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" 
                            @click="navigator.clipboard.writeText('{{ $latestInvoice->control_number }}'); copied = true; setTimeout(() => copied = false, 3000)" 
                            class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-5 py-3 rounded-xl text-xs uppercase tracking-wider flex items-center gap-2 transition shadow-lg cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        <span x-text="copied ? 'Copied!' : 'Copy Control Number'"></span>
                    </button>
                    <a href="{{ route('portal.payments') }}" class="bg-slate-800 hover:bg-slate-700 text-emerald-300 border border-emerald-600/50 font-bold px-4 py-3 rounded-xl text-xs transition">
                        Submit Payment Proof &rarr;
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Main Grid: Digital ID Card Preview & Summary Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Left Column: Digital ID Card Widget -->
        <div class="lg:col-span-5 space-y-6" x-data="{ isFlipped: false }">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 font-heading">Digital Membership Card</h2>
                        <span class="text-[10px] text-slate-400">CR80 Smart Badge</span>
                    </div>
                    <button type="button" @click="isFlipped = !isFlipped" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span x-text="isFlipped ? 'Front View' : 'Back View'"></span>
                    </button>
                </div>

                <!-- 3D Card Container -->
                <div class="perspective-1000 py-1 flex justify-center" style="perspective: 1000px;">
                    <div class="w-full relative shadow-xl cursor-pointer rounded-2xl transition-transform duration-500 ease-in-out"
                        style="aspect-ratio: 85.6 / 53.98; transform-style: preserve-3d;"
                        :style="isFlipped ? 'transform: rotateY(180deg)' : 'transform: rotateY(0deg)'"
                        @click="isFlipped = !isFlipped"
                        title="Click to flip card">

                        <!-- FRONT FACE -->
                        <div class="absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white border border-emerald-500/40 shadow-2xl"
                            style="backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                            
                            <!-- National Flag Stripe -->
                            <div class="h-1 w-full flex -mt-4 sm:-mt-5 -mx-4 sm:-mx-5 mb-2">
                                <div class="w-1/3 bg-[#1eb53a]"></div>
                                <div class="w-1/3 bg-[#fcd116]"></div>
                                <div class="w-1/3 bg-[#00a3dd]"></div>
                            </div>

                            <!-- Header -->
                            <div class="flex items-center justify-between pb-2 border-b border-white/10">
                                <div class="flex items-center gap-2">
                                    @if(\App\Models\Setting::hasCustomLogo())
                                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-7 max-w-[36px] object-contain">
                                    @else
                                        <div class="w-7 h-7 rounded-lg bg-emerald-600 flex items-center justify-center font-bold text-white text-[10px] shadow-xs">TEV</div>
                                    @endif
                                    <div>
                                        <span class="text-xs font-black tracking-tight block font-heading leading-tight">TEVDA</span>
                                        <span class="text-[6.5px] uppercase font-bold tracking-wider text-emerald-400 block">Tanzania Electric Vehicles Drivers Association</span>
                                    </div>
                                </div>
                                <span class="text-[8px] uppercase font-bold tracking-wider bg-emerald-900/80 text-emerald-300 px-2.5 py-0.5 rounded-md border border-emerald-600 shadow-xs">
                                    {{ $member->category->name }}
                                </span>
                            </div>

                            <!-- Body -->
                            <div class="grid grid-cols-12 gap-3 items-center my-auto">
                                <div class="col-span-3">
                                    @if ($member->passport_photo_path)
                                        <img src="{{ asset('storage/' . $member->passport_photo_path) }}" alt="Photo" class="w-14 h-18 sm:w-16 sm:h-20 object-cover rounded-xl border-2 border-emerald-500/60 shadow-md">
                                    @else
                                        <div class="w-14 h-18 sm:w-16 sm:h-20 rounded-xl bg-slate-800/80 border border-slate-700 flex flex-col items-center justify-center text-slate-400 text-[8px] font-bold">
                                            <svg class="w-5 h-5 mb-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>PHOTO</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-6 space-y-1">
                                    <div>
                                        <span class="text-[7.5px] text-slate-400 block uppercase font-semibold leading-none">Member Name / Jina</span>
                                        <strong class="text-white block font-black text-xs sm:text-sm truncate tracking-tight">{{ $member->full_name }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] text-slate-400 block uppercase font-semibold leading-none">Member ID / Namba</span>
                                        <span class="text-amber-400 font-mono font-bold text-xs inline-block px-1.5 py-0.5 rounded bg-emerald-950 border border-amber-500/60">{{ $member->membership_number ?? 'PENDING' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] text-slate-400 block uppercase font-semibold leading-none">Region & Territory</span>
                                        <span class="text-slate-200 font-semibold text-[10px] block">{{ $member->region?->name ?? 'Tanzania' }} @if($member->district) • {{ $member->district->name }} @endif</span>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] text-slate-400 block uppercase font-semibold leading-none">Validity</span>
                                        <span class="text-[9.5px] font-bold">
                                            <span class="text-amber-400">{{ $member->expiry_date ? 'EXP: ' . $member->expiry_date->format('m/Y') : 'ACTIVE' }}</span>
                                            <span class="text-emerald-400 ml-1">• VERIFIED</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-span-3 flex flex-col items-end">
                                    @if (!empty($qrCodeUri))
                                        <div class="bg-white p-1 rounded-xl shadow-md border border-slate-200">
                                            <img src="{{ $qrCodeUri }}" alt="QR" class="w-11 h-11 sm:w-12 sm:h-12">
                                        </div>
                                        <span class="text-[6px] text-slate-400 uppercase font-bold tracking-wider mt-1">Scan to Verify</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Micro Security Strip -->
                            <div class="text-[6px] text-center uppercase tracking-widest text-emerald-400/80 font-mono py-0.5 bg-black/30 -mx-4 sm:-mx-5">
                                • TANZANIA ELECTRIC VEHICLES DRIVERS ASSOCIATION • OFFICIAL SECURE SMART ID • TEVDA CERTIFIED •
                            </div>

                            <div class="pt-1.5 border-t border-white/10 flex justify-between items-center text-[7.5px]">
                                <span class="tracking-wider text-amber-400 font-bold uppercase">SMART DRIVERS SMART MOBILITY</span>
                                <span class="text-slate-400 uppercase font-bold">WWW.TEVDA.OR.TZ</span>
                            </div>
                        </div>

                        <!-- BACK FACE -->
                        <div class="absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white border border-emerald-500/40 shadow-2xl"
                            style="backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="h-7 bg-slate-950 -mx-5 -mt-5 px-5 flex items-center justify-between border-b border-white/10">
                                <span class="font-mono text-[8px] text-slate-300 font-bold tracking-wider">CARD-{{ $member->membership_number }}</span>
                                <span class="text-[7px] text-emerald-400 font-bold uppercase tracking-wider">OFFICIAL SMART BADGE</span>
                            </div>

                            <div class="text-[7.5px] leading-relaxed text-slate-400 text-left my-1">
                                This official smart identification card certifies that the cardholder is a registered and compliant member of the Tanzania Electric Vehicles Drivers Association (TEVDA). Card is non-transferable and must be presented upon request during official operations.
                            </div>

                            <div class="grid grid-cols-2 gap-3 items-end">
                                <div class="space-y-0.5">
                                    <span class="text-[6.5px] uppercase text-slate-400 block font-semibold">Authorized Signatory</span>
                                    <div class="border-b border-slate-600 pb-0.5">
                                        <span class="font-serif italic text-sm text-emerald-400">{{ \App\Models\Setting::get('chairman_name', 'Charles Mwansasu') }}</span>
                                    </div>
                                    <span class="text-[7.5px] font-bold block text-white">{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}</span>
                                    <span class="text-[6.5px] text-slate-400 block">{{ \App\Models\Setting::get('chairman_role', 'Founding Chairperson') }} • TEVDA</span>
                                </div>

                                <div class="p-2 rounded-xl bg-slate-900/90 text-[7px] leading-tight space-y-0.5 border border-white/5 text-slate-300">
                                    <strong class="text-emerald-400 block font-bold text-[7.5px]">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                                    <p>{{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}</p>
                                    <p class="font-mono font-bold">Helpline: {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}</p>
                                    <p>Support: {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }} • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}</p>
                                </div>
                            </div>

                            <!-- Barcode -->
                            <div class="text-center font-mono text-[8px] text-slate-400 tracking-widest py-0.5">
                                ||| | |||| | ||| |||| | || ||| |||| | || | |||
                            </div>

                            <!-- Micro Security Strip -->
                            <div class="text-[6px] text-center uppercase tracking-widest text-emerald-400/80 font-mono py-0.5 bg-black/30 -mx-4 sm:-mx-5">
                                • PROPERTY OF TEVDA • ENCRYPTED SMART ID • ISO/IEC 7810 ID-1 •
                            </div>

                            <div class="text-[6.5px] text-center text-amber-400 font-bold uppercase tracking-wider pt-1 border-t border-white/5">
                                IF FOUND PLEASE RETURN TO ANY TEVDA OFFICE OR POLICE STATION
                            </div>
                        </div>

                    </div>
                </div>

                @if ($member->status === 'approved')
                    <a href="{{ route('portal.card.download') }}" class="w-full bg-emerald-600 hover:bg-emerald-500 text-slate-950 font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center justify-center gap-2 shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        <span>Download Printable ID Card (PDF)</span>
                    </a>
                @endif
            </div>

            <!-- Vehicle Summary -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-3">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider font-heading">Primary Vehicle Information</h3>
                @if ($member->primaryVehicle)
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-1.5 text-slate-700">
                        <p><strong>Type:</strong> {{ ucwords(str_replace('_', ' ', $member->primaryVehicle->vehicle_type)) }}</p>
                        <p><strong>Make & Model:</strong> {{ $member->primaryVehicle->make ?? 'N/A' }} {{ $member->primaryVehicle->model ?? '' }}</p>
                        <p><strong>Registration:</strong> <span class="font-mono font-bold text-slate-900">{{ $member->primaryVehicle->registration_number ?? 'Pending Registration' }}</span></p>
                        <p><strong>Charging Mode:</strong> {{ ucwords(str_replace('_', ' ', $member->primaryVehicle->charging_type)) }}</p>
                    </div>
                @else
                    <p class="text-xs text-slate-500">No primary vehicle registered. Update in your profile if you operate a commercial EV.</p>
                @endif
            </div>
        </div>

        <!-- Right Column: Training, Certificates & Invoices -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Training Enrolments -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 font-heading">My Training Sessions</h3>
                    <a href="{{ route('portal.training') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">Browse Programmes</a>
                </div>

                <div class="space-y-3">
                    @forelse (($enrolments ?? collect()) as $enrolment)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">{{ $enrolment->session->session_code }}</span>
                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 mt-1">{{ $enrolment->session->course->title }}</h4>
                                <p class="text-[11px] text-slate-500">Starts {{ $enrolment->session->start_date->format('d M Y') }} • Location: {{ $enrolment->session->location }}</p>
                            </div>
                            <div>
                                @if ($enrolment->status === 'completed')
                                    <span class="text-[10px] font-bold bg-emerald-600 text-white px-3 py-1 rounded-full">PASSED</span>
                                @elseif ($enrolment->status === 'failed')
                                    <span class="text-[10px] font-bold bg-rose-600 text-white px-3 py-1 rounded-full">RE-TAKE</span>
                                @else
                                    <span class="text-[10px] font-bold bg-slate-200 text-slate-700 px-3 py-1 rounded-full">{{ strtoupper($enrolment->status) }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-3 text-center">You have not registered for any training sessions yet.</p>
                    @endforelse
                </div>
            </div>

            <!-- Issued Certificates -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 font-heading">My Official Certificates</h3>
                    <span class="text-xs text-slate-500">{{ ($certificates ?? collect())->count() }} Issued</span>
                </div>

                <div class="space-y-3">
                    @forelse (($certificates ?? collect()) as $cert)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-cyan-700 bg-cyan-100 px-2 py-0.5 rounded-full">{{ $cert->certificate_type }}</span>
                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 mt-1">{{ $cert->title }}</h4>
                                <p class="text-[11px] font-mono text-slate-500">{{ $cert->certificate_number }} • Issued {{ $cert->issue_date->format('d M Y') }}</p>
                            </div>
                            <a href="{{ route('portal.certificate.download', $cert->id) }}" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-200">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                <span>PDF</span>
                            </a>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-3 text-center">Certificates will appear here once approved or completed.</p>
                    @endforelse
                </div>
            </div>

            <!-- Invoices & Payments -->
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex justify-between items-center pb-2 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-900 font-heading">Recent Invoices</h3>
                    <a href="{{ route('portal.payments') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800">View Payments</a>
                </div>

                <div class="space-y-3">
                    @forelse (($invoices ?? collect()) as $inv)
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 flex justify-between items-center">
                            <div>
                                <span class="text-xs font-bold text-slate-900">{{ $inv->purpose }}</span>
                                <p class="text-[11px] font-mono text-slate-500">{{ $inv->invoice_number }} • {{ number_format($inv->amount) }} {{ $inv->currency }}</p>
                            </div>
                            <span class="text-[10px] font-bold px-2.5 py-1 rounded-full {{ $inv->status === 'paid' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ strtoupper($inv->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 py-2 text-center">No outstanding invoices on your account.</p>
                    @endforelse
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
