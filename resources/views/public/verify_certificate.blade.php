@extends('layouts.app')

@section('title', $certificate ? 'Verified Certificate: ' . $certificate->recipient_name . ' (' . $certificate->certificate_number . ') — TEVDA Registry' : 'Verify Certificate Authenticity — TEVDA Public Registry')

@section('content')
<div class="relative hero-pattern text-white py-12 lg:py-16 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 text-xs font-extrabold uppercase tracking-widest text-cyan-300 bg-cyan-950/90 px-4 py-1.5 rounded-full border border-cyan-700/80 shadow-inner mb-3">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            <span>Official Certificate Verification Registry</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-2 mb-3 text-white tracking-tight">Certificate Verification</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl mx-auto leading-relaxed">
            Verify the authenticity, issuance credentials, and active validity of official TEVDA Membership, Training Completion, and Professional Competency certificates.
        </p>
    </div>
</div>

<div class="py-12 bg-slate-50/60 subtle-grid-pattern min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Search Box -->
        <div class="bg-white p-5 sm:p-7 rounded-3xl border border-slate-200/90 shadow-sm">
            <form action="{{ route('verify.certificate') }}" method="GET" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-black text-slate-700 uppercase tracking-wider">Search By Certificate Number or Member ID</label>
                    <a href="{{ route('verify.membership') }}" class="text-xs text-cyan-700 font-bold hover:underline flex items-center gap-1">
                        <span>Membership & ID Card Registry &rarr;</span>
                    </a>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="text" name="number" value="{{ $certificateNumber }}" placeholder="e.g. TEVDA-CERT-2026-000001 or TEVDA-2026-..." required class="flex-1 bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-cyan-500 font-mono transition">
                    <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white font-black px-8 py-3.5 rounded-xl text-xs sm:text-sm transition shadow-md shadow-cyan-600/20 shrink-0 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-award"></i>
                        <span>Verify Certificate</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Result -->
        @if ($searchPerformed)
            @if ($certificate)
                <div class="space-y-6">

                    <!-- Official Verification Banner -->
                    <div class="p-4 sm:p-5 rounded-3xl border-2 {{ $certificate->status === 'valid' ? 'bg-gradient-to-r from-teal-950 via-slate-900 to-emerald-950 border-emerald-500 text-white shadow-xl shadow-teal-950/20' : ($certificate->status === 'revoked' ? 'bg-rose-950 border-rose-500 text-white shadow-xl' : 'bg-amber-50 border-amber-400 text-amber-950') }} flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-3.5 text-center sm:text-left">
                            <div class="w-12 h-12 rounded-2xl {{ $certificate->status === 'valid' ? 'bg-emerald-500 text-slate-950' : ($certificate->status === 'revoked' ? 'bg-rose-600 text-white' : 'bg-amber-500 text-white') }} flex items-center justify-center text-xl shrink-0 shadow-md font-black">
                                @if ($certificate->status === 'valid')
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                @elseif ($certificate->status === 'revoked')
                                    <i class="fa-solid fa-ban"></i>
                                @else
                                    <i class="fa-solid fa-clock"></i>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center justify-center sm:justify-start gap-2">
                                    <span class="text-[10px] uppercase font-black tracking-widest {{ $certificate->status === 'valid' ? 'text-emerald-300' : ($certificate->status === 'revoked' ? 'text-rose-300' : 'text-amber-800') }}">Authenticity Verified</span>
                                    <span class="inline-block w-1.5 h-1.5 rounded-full {{ $certificate->status === 'valid' ? 'bg-emerald-400 animate-ping' : 'bg-rose-400' }}"></span>
                                </div>
                                <h2 class="text-lg sm:text-xl font-black font-heading tracking-tight text-white">
                                    @if ($certificate->status === 'valid')
                                        VERIFIED AUTHENTIC OFFICIAL CERTIFICATE
                                    @elseif ($certificate->status === 'revoked')
                                        REVOKED CERTIFICATE
                                    @elseif ($certificate->status === 'replaced')
                                        REPLACED BY SUPERSEDED CERTIFICATE
                                    @else
                                        STATUS: {{ strtoupper($certificate->status) }}
                                    @endif
                                </h2>
                                <p class="text-xs {{ $certificate->status === 'valid' ? 'text-emerald-200/80' : 'text-slate-300' }} mt-0.5">
                                    Certificate record authenticated against the official TEVDA national database registry. Verified on {{ now()->format('d M Y, H:i') }} EAT.
                                </p>
                            </div>
                        </div>

                        @if ($certificate->status === 'valid')
                            <div class="shrink-0 flex items-center gap-2">
                                <a href="{{ route('public.certificate.download', $certificate->certificate_number) }}" class="bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition shadow-md">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span>Download PDF</span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Revocation notice if revoked -->
                    @if ($certificate->status === 'revoked' && $certificate->revocation_reason)
                        <div class="p-4 bg-rose-100 border border-rose-300 rounded-2xl text-xs text-rose-900">
                            <strong>Official Revocation Reason:</strong> {{ $certificate->revocation_reason }}
                        </div>
                    @endif

                    <!-- DIGITAL CERTIFICATE DISPLAY (APPEARS PROMINENTLY ON SCAN) -->
                    @if ($certificate->status === 'valid')
                        <div class="bg-white p-4 sm:p-8 rounded-3xl border border-slate-200/90 shadow-xl overflow-hidden">
                            <div class="flex items-center justify-between pb-4 mb-4 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-pulse"></span>
                                    <h3 class="text-lg font-black text-slate-900 font-heading">Digital Certificate Preview</h3>
                                </div>
                                <span class="text-xs font-mono font-bold bg-slate-100 text-slate-700 px-3 py-1 rounded-lg border border-slate-200">
                                    {{ $certificate->certificate_number }}
                                </span>
                            </div>

                            <!-- Certificate Frame -->
                            <div class="relative bg-[#ffffff] p-6 sm:p-10 rounded-2xl border-4 sm:border-8 border-double border-[#065f46] text-[#0f172a] shadow-inner overflow-hidden text-center">
                                <!-- Watermark background -->
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none select-none opacity-5">
                                    <span class="text-8xl sm:text-9xl font-black text-emerald-900 font-serif">TEVDA</span>
                                </div>

                                <!-- Security Corners Decoration -->
                                <div class="absolute top-2 left-2 w-6 h-6 border-t-2 border-l-2 border-amber-500"></div>
                                <div class="absolute top-2 right-2 w-6 h-6 border-t-2 border-r-2 border-amber-500"></div>
                                <div class="absolute bottom-2 left-2 w-6 h-6 border-b-2 border-l-2 border-amber-500"></div>
                                <div class="absolute bottom-2 right-2 w-6 h-6 border-b-2 border-r-2 border-amber-500"></div>

                                <!-- Top Header -->
                                <div class="relative z-10 space-y-2 mb-4">
                                    @if(\App\Models\Setting::hasCustomLogo())
                                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="TEVDA Official Crest" class="h-14 sm:h-18 mx-auto object-contain">
                                    @else
                                        <div class="w-14 h-14 rounded-2xl bg-emerald-700 text-white mx-auto flex items-center justify-center font-black text-xl shadow-md border-2 border-emerald-500">
                                            TEV
                                        </div>
                                    @endif

                                    <div class="inline-block bg-emerald-50 border border-emerald-300 text-[#065f46] font-serif font-black px-4 sm:px-6 py-1 rounded-full text-xs sm:text-sm uppercase tracking-wider shadow-xs mt-1">
                                        Tanzania Electric Vehicles Drivers Association
                                    </div>
                                    
                                    <div class="text-[10px] sm:text-xs font-bold text-amber-600 uppercase tracking-widest">
                                        SMART DRIVERS SMART MOBILITY
                                    </div>
                                </div>

                                <!-- Certificate Title -->
                                <div class="relative z-10 space-y-1 mb-5">
                                    <h2 class="text-xl sm:text-3xl lg:text-4xl font-black text-[#0f2744] font-serif tracking-tight">
                                        {{ $certificate->title ?? 'Certificate of Achievement' }}
                                    </h2>
                                    <div class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest">
                                        THIS IS TO OFFICIALLY CERTIFY THAT
                                    </div>
                                </div>

                                <!-- Recipient Name -->
                                <div class="relative z-10 mb-5">
                                    <div class="inline-block border-b-4 border-amber-400 pb-2 px-6 sm:px-12">
                                        <span class="text-2xl sm:text-4xl lg:text-5xl font-black font-serif text-[#065f46] tracking-tight">
                                            {{ $certificate->recipient_name }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Certificate Body Statement -->
                                <div class="relative z-10 max-w-2xl mx-auto space-y-2 mb-8 text-xs sm:text-base text-slate-700 leading-relaxed font-sans">
                                    <p>
                                        has successfully fulfilled all institutional requirements and accreditation standards of the association as an accredited
                                    </p>
                                    @if ($certificate->course_name)
                                        <p class="font-bold text-slate-900 text-sm sm:text-lg bg-emerald-50/70 p-2 rounded-xl border border-emerald-100 inline-block">
                                            {{ $certificate->course_name }}
                                        </p>
                                    @endif
                                    @if ($certificate->grade)
                                        <p class="text-xs sm:text-sm font-bold text-emerald-800">
                                            Conferred with distinction: {{ $certificate->grade }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Signatures & Seals Grid -->
                                <div class="relative z-10 pt-4 border-t border-slate-200 grid grid-cols-3 gap-2 sm:gap-4 items-end text-left">
                                    
                                    <!-- Left: Issue & Cert No -->
                                    <div class="space-y-1 text-[9px] sm:text-xs">
                                        <div>
                                            <span class="text-slate-400 font-semibold block uppercase text-[8px] sm:text-[10px]">Issue Date</span>
                                            <strong class="text-slate-800 font-bold block">{{ $certificate->issue_date->format('d F Y') }}</strong>
                                        </div>
                                        <div>
                                            <span class="text-slate-400 font-semibold block uppercase text-[8px] sm:text-[10px]">Certificate No</span>
                                            <span class="font-mono font-bold text-emerald-700 block text-[8px] sm:text-[11px]">{{ $certificate->certificate_number }}</span>
                                        </div>
                                        @if ($certificate->expiry_date)
                                            <div>
                                                <span class="text-slate-400 font-semibold block uppercase text-[8px] sm:text-[10px]">Valid Until</span>
                                                <strong class="text-amber-700 font-bold block">{{ $certificate->expiry_date->format('d F Y') }}</strong>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Center: Gold Official Seal -->
                                    <div class="text-center flex flex-col items-center justify-center">
                                        <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-gradient-to-tr from-amber-600 via-amber-400 to-yellow-300 p-1 shadow-lg flex items-center justify-center">
                                            <div class="w-full h-full rounded-full border-2 border-dashed border-amber-900 flex flex-col items-center justify-center text-amber-950 p-1 text-center bg-gradient-to-b from-amber-100 to-amber-200">
                                                <i class="fa-solid fa-award text-sm sm:text-lg text-amber-800"></i>
                                                <span class="text-[6px] sm:text-[7.5px] font-black uppercase tracking-tighter block leading-none mt-0.5">TEVDA SEAL</span>
                                                <span class="text-[5px] sm:text-[6px] font-bold text-emerald-900 block leading-none">VERIFIED</span>
                                            </div>
                                        </div>
                                        <span class="text-[7px] sm:text-[8.5px] font-extrabold uppercase text-amber-700 tracking-wider mt-1 block">OFFICIAL CREDENTIAL</span>
                                    </div>

                                    <!-- Right: Signature & QR -->
                                    <div class="text-right flex flex-col items-end space-y-1">
                                        <div class="border-b border-slate-600 pb-0.5 min-h-[30px] flex items-center justify-end">
                                            @if(\App\Models\Setting::hasChairmanSignature())
                                                <img src="{{ \App\Models\Setting::getChairmanSignatureUrl() }}" alt="Signature" class="max-h-8 sm:max-h-10 max-w-[120px] object-contain">
                                            @else
                                                <span class="font-serif italic text-base sm:text-lg text-emerald-800">{{ $certificate->authorized_person_name ?? 'Dr. Charles Mwansasu' }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <strong class="text-slate-900 block text-[9px] sm:text-xs font-bold">{{ $certificate->authorized_person_name ?? 'Dr. Charles Mwansasu' }}</strong>
                                            <span class="text-[7.5px] sm:text-[9.5px] text-slate-500 block leading-tight">{{ $certificate->authorized_person_title ?? 'Founding & National Chairperson' }}</span>
                                        </div>

                                        @if (!empty($qrCodeUri))
                                            <div class="pt-1.5 flex items-center gap-1.5 justify-end">
                                                <img src="{{ $qrCodeUri }}" alt="Verification QR Code" class="w-10 h-10 sm:w-14 sm:h-14 border border-slate-300 rounded p-0.5 bg-white shadow-xs">
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Detailed Certificate Metadata Grid -->
                    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm space-y-6">
                        <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                            <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center font-bold">
                                <i class="fa-solid fa-file-circle-check text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-900 font-heading">Official Registry Certificate Metadata</h3>
                                <p class="text-xs text-slate-500">Cryptographically verifiable record details registered at Secretariat.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs sm:text-sm text-slate-700">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Certificate Number</span>
                                <strong class="text-cyan-700 font-mono text-sm font-black block mt-0.5">{{ $certificate->certificate_number }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Recipient Name</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $certificate->recipient_name }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Certificate Type</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ ucwords(str_replace('_', ' ', $certificate->certificate_type)) }}</strong>
                            </div>

                            @if ($certificate->course_name)
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 sm:col-span-2">
                                    <span class="text-[11px] text-slate-400 font-semibold uppercase block">Course / Qualification</span>
                                    <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $certificate->course_name }}</strong>
                                </div>
                            @endif

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Date of Issuance</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $certificate->issue_date->format('d F Y') }}</strong>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Authorized Authority</span>
                                <strong class="text-slate-900 text-sm font-bold block mt-0.5">{{ $certificate->authorized_person_name }}</strong>
                                <span class="text-[11px] text-slate-500 block">{{ $certificate->authorized_person_title }}</span>
                            </div>

                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200">
                                <span class="text-[11px] text-slate-400 font-semibold uppercase block">Verification Timestamp</span>
                                <strong class="text-emerald-700 text-sm font-bold block mt-0.5">{{ now()->format('d M Y, H:i:s') }} EAT</strong>
                            </div>
                        </div>

                        <!-- Instant Action Buttons -->
                        @if ($certificate->status === 'valid')
                            <div class="p-5 bg-gradient-to-r from-slate-900 via-teal-950 to-slate-900 rounded-2xl border border-cyan-500/40 text-white space-y-3">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2">
                                    <div>
                                        <span class="text-[10px] uppercase font-black tracking-widest text-cyan-300">Verified Official Credential</span>
                                        <h4 class="text-sm font-black font-heading text-white">Download Authentic High-Resolution PDF</h4>
                                    </div>
                                    <span class="text-[10px] text-cyan-300 font-mono bg-cyan-900/60 px-2.5 py-1 rounded-full border border-cyan-600/50">Status: Authenticated</span>
                                </div>

                                <div class="flex flex-wrap gap-2.5 pt-1">
                                    <a href="{{ route('public.certificate.download', $certificate->certificate_number) }}" 
                                       class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-400 hover:to-teal-400 text-slate-950 font-black px-5 py-2.5 rounded-xl text-xs flex items-center gap-2 transition shadow-md">
                                        <i class="fa-solid fa-file-pdf"></i>
                                        <span>Download Official Certificate (PDF)</span>
                                    </a>

                                    @if ($certificate->member && $certificate->member->status === 'approved' && $certificate->member->membership_number)
                                        <a href="{{ route('public.card.download', $certificate->member->membership_number) }}" 
                                           class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition">
                                            <i class="fa-solid fa-id-card text-emerald-400"></i>
                                            <span>Download Member ID Card</span>
                                        </a>
                                        <a href="{{ route('verify.membership', $certificate->member->membership_number) }}" 
                                           class="bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center gap-2 transition">
                                            <i class="fa-solid fa-user-check text-cyan-400"></i>
                                            <span>View Member Profile</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <div class="p-4 bg-slate-950 rounded-2xl text-slate-300 text-xs flex justify-between items-center">
                            <span class="text-emerald-400 font-bold">SMART DRIVERS SMART MOBILITY</span>
                            <span class="text-slate-400 text-[11px]">Tanzania Electric Vehicles Drivers Association (TEVDA)</span>
                        </div>
                    </div>

                </div>
            @else
                <div class="p-8 bg-rose-50 border-2 border-rose-200 rounded-3xl text-center text-rose-900 space-y-3 shadow-xs">
                    <svg class="w-12 h-12 text-rose-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="text-lg font-bold">Invalid or Unrecognized Certificate Number</h3>
                    <p class="text-xs text-rose-700 max-w-md mx-auto">
                        No certificate record found matching "<strong>{{ $certificateNumber }}</strong>". Please verify the code printed on the physical certificate or QR code scanner.
                    </p>
                    <div class="pt-2 flex justify-center gap-2">
                        <a href="{{ route('verify.membership', ['query' => $certificateNumber]) }}" class="px-5 py-2.5 bg-rose-200 hover:bg-rose-300 text-rose-950 font-bold text-xs rounded-xl transition">
                            Search In Membership Registry
                        </a>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
