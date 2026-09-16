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
                        Your application is undergoing verification. ID Card & Certificate will be unlocked upon approval.
                    </div>
                @endif
            </div>
        </div>
    </div>

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
                        <div class="absolute inset-0 rounded-2xl p-5 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-900 via-emerald-950 to-slate-950 text-white border border-emerald-500/30 shadow-lg"
                            style="backface-visibility: hidden; -webkit-backface-visibility: hidden;">
                            <div class="flex justify-between items-start">
                                <div class="flex items-center gap-2">
                                    @if(\App\Models\Setting::hasCustomLogo())
                                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-8 max-w-[40px] object-contain">
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-emerald-600 flex items-center justify-center font-bold text-white text-xs">TEV</div>
                                    @endif
                                    <div>
                                        <span class="text-xs font-black tracking-tight block font-heading">TEVDA</span>
                                        <span class="text-[7px] uppercase tracking-widest text-emerald-400 block font-semibold">Tanzania EV Drivers Association</span>
                                    </div>
                                </div>
                                <span class="text-[8px] uppercase font-bold tracking-wider bg-emerald-900/80 text-emerald-300 px-2 py-0.5 rounded-md border border-emerald-700">
                                    {{ $member->category->name }}
                                </span>
                            </div>

                            <div class="grid grid-cols-12 gap-3 items-center my-auto">
                                <div class="col-span-4">
                                    @if ($member->passport_photo_path)
                                        <img src="{{ asset('storage/' . $member->passport_photo_path) }}" alt="Photo" class="w-16 h-20 sm:w-18 sm:h-22 object-cover rounded-xl border-2 border-emerald-500/50 shadow-xs">
                                    @else
                                        <div class="w-16 h-20 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 text-xs font-bold">
                                            PHOTO
                                        </div>
                                    @endif
                                </div>
                                <div class="col-span-5 space-y-1 text-[11px]">
                                    <div>
                                        <span class="text-[8px] text-slate-400 block uppercase font-semibold">Member Name</span>
                                        <strong class="text-white block font-bold text-xs truncate">{{ $member->full_name }}</strong>
                                    </div>
                                    <div>
                                        <span class="text-[8px] text-slate-400 block uppercase font-semibold">Member Number</span>
                                        <span class="text-emerald-400 font-mono font-bold block">{{ $member->membership_number ?? 'PENDING' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-[8px] text-slate-400 block uppercase font-semibold">Region / Valid</span>
                                        <span class="text-slate-200 font-semibold text-[10px]">{{ $member->region?->name ?? 'Tanzania' }} • <strong class="text-amber-400">{{ $member->expiry_date ? $member->expiry_date->format('m/Y') : 'ACTIVE' }}</strong></span>
                                    </div>
                                </div>
                                <div class="col-span-3 flex flex-col items-end">
                                    @if ($qrCodeUri)
                                        <div class="bg-white p-1 rounded-md shadow-xs">
                                            <img src="{{ $qrCodeUri }}" alt="QR" class="w-10 h-10">
                                        </div>
                                        <span class="text-[6px] text-slate-400 uppercase mt-0.5">VERIFIED</span>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-2 border-t border-slate-800 flex justify-between items-center text-[8px]">
                                <span class="tracking-wider text-emerald-400 font-bold uppercase">SMART DRIVERS SMART MOBILITY</span>
                                <span class="text-slate-400 uppercase font-semibold">WWW.TEVDA.OR.TZ</span>
                            </div>
                        </div>

                        <!-- BACK FACE -->
                        <div class="absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white border border-emerald-500/30 shadow-lg"
                            style="backface-visibility: hidden; -webkit-backface-visibility: hidden; transform: rotateY(180deg);">
                            <div class="h-6 bg-slate-950 -mx-5 -mt-5 px-5 flex items-center justify-between border-b border-white/5">
                                <span class="font-mono text-[8px] text-slate-400 tracking-wider">CARD-{{ $member->membership_number }}</span>
                                <span class="text-[7px] text-emerald-400 font-bold uppercase">OFFICIAL TEVDA BADGE</span>
                            </div>

                            <div class="text-[8px] leading-tight text-slate-400 text-justify my-1">
                                This official card certifies that the holder is a verified member of TEVDA. Non-transferable and must be presented upon official request.
                            </div>

                            <div class="grid grid-cols-2 gap-3 items-end">
                                <div class="space-y-0.5">
                                    <span class="text-[7px] uppercase text-slate-400 block">Authorized Signature</span>
                                    <div class="border-b border-slate-600 pb-0.5">
                                        <span class="font-serif italic text-xs text-emerald-400">Charles Mwansasu</span>
                                    </div>
                                    <span class="text-[7.5px] font-bold block text-white">Dr. Charles Mwansasu</span>
                                    <span class="text-[6.5px] text-slate-400 block">Chairperson • TEVDA</span>
                                </div>

                                <div class="p-2 rounded-xl bg-slate-900/90 text-[7.5px] leading-tight space-y-0.5 border border-white/5 text-slate-300">
                                    <strong class="text-emerald-400 block font-bold text-[8px]">TEVDA HELPLINE</strong>
                                    <p>Dar es Salaam, Tanzania</p>
                                    <p class="font-mono">+255 700 000 000</p>
                                    <p>info@tevda.or.tz</p>
                                </div>
                            </div>

                            <div class="text-[7px] text-center text-slate-500 uppercase tracking-wider pt-1 border-t border-white/5">
                                Property of TEVDA. If found, return to nearest TEVDA branch.
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
                    @forelse ($enrolments as $enrolment)
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
                    <span class="text-xs text-slate-500">{{ $certificates->count() }} Issued</span>
                </div>

                <div class="space-y-3">
                    @forelse ($certificates as $cert)
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
                    @forelse ($invoices as $inv)
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
