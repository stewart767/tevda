@extends('layouts.app')

@section('title', 'Track Application Status & Payment Control Number — TEVDA')

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-950 text-white py-14 lg:py-18 overflow-hidden border-b border-emerald-900/40">
    <div class="absolute -left-20 -top-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-900/80 border border-emerald-700/60 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest text-emerald-300 mb-3 shadow-xs">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Live Application Tracking & Payment Portal</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black font-heading tracking-tight text-white mb-3">
            Track Membership Application
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
            Enter your <strong>Driving Licence Number</strong>, <strong>Mobile Phone Number</strong>, <strong>Membership Number</strong>, or <strong>Payment Control Number</strong> to check your registration progress, view your Control Number, or download your Certificate.
        </p>
    </div>
</div>

<div class="py-12 lg:py-16 bg-slate-50 min-h-[60vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        @include('partials.alerts')

        <!-- Search Card -->
        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xl relative">
            <form action="{{ route('track.application') }}" method="GET" class="space-y-3">
                <div class="flex items-center justify-between">
                    <label for="query" class="block text-xs font-black text-slate-800 uppercase tracking-wider">
                        Search By Driving Licence, Phone Number, or Control Number
                    </label>
                    <span class="text-[11px] text-emerald-700 font-bold hidden sm:inline-block">Instant Real-Time Verification</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" 
                               id="query" 
                               name="query" 
                               value="{{ $searchQuery }}" 
                               required 
                               placeholder="e.g. 0757700401, DL-123456, TEVDA-2026-00001, or 994010001001" 
                               class="w-full bg-slate-50 border border-slate-300 rounded-2xl pl-11 pr-4 py-3.5 text-xs sm:text-sm font-medium text-slate-900 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition shadow-inner">
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black px-8 py-3.5 rounded-2xl text-xs sm:text-sm transition-all duration-200 shadow-md shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        <span>Track Status</span>
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2 pt-1 text-[11px] text-slate-500">
                    <span class="font-semibold text-slate-600">Quick suggestions:</span>
                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-mono">Driving Licence: DL-XXXXXX</span>
                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-mono">Phone: 0757 700 401</span>
                    <span class="bg-slate-100 text-slate-700 px-2 py-0.5 rounded-md font-mono">Control No: 99401XXXXXXX</span>
                </div>
            </form>
        </div>

        <!-- Result Section -->
        @if ($searchPerformed)
            @if ($member)
                @php
                    $isApproved = ($member->status === 'approved');
                    $isPaid = ($invoice && $invoice->status === 'paid');
                    $hasControlNumber = ($invoice && !empty($invoice->control_number));
                @endphp

                <div class="space-y-6">
                    
                    <!-- 1. Header Profile & Overall Status Card -->
                    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full pointer-events-none"></div>

                        <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4 pb-6 border-b border-slate-100 relative z-10">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-emerald-950 border-2 border-emerald-500/40 text-emerald-400 flex items-center justify-center font-black text-2xl shadow-lg shrink-0 overflow-hidden">
                                    @if ($member->passport_photo_path)
                                        <img src="{{ \App\Helpers\ImageHelper::getUrl($member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                        <span class="hidden">{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                                    @else
                                        <span>{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="space-y-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-[10px] uppercase font-extrabold bg-emerald-100 text-emerald-800 px-2.5 py-0.5 rounded-full">
                                            {{ $member->category->name }}
                                        </span>
                                        @if($member->is_founding_member)
                                            <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">Founding Driver</span>
                                        @endif
                                    </div>
                                    <h2 class="text-xl sm:text-2xl font-black text-slate-900 font-heading">
                                        {{ $member->full_name }}
                                    </h2>
                                    <p class="text-xs text-slate-500 flex flex-wrap items-center gap-3">
                                        <span>Phone: <strong class="text-slate-800 font-mono">{{ $member->phone }}</strong></span>
                                        <span>•</span>
                                        <span>Region: <strong class="text-slate-800">{{ $member->region?->name ?? 'Tanzania' }}</strong></span>
                                        @if($member->driving_licence_number)
                                            <span>•</span>
                                            <span>Licence: <strong class="text-slate-800 font-mono">{{ $member->driving_licence_number }}</strong></span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="shrink-0 flex flex-col items-start sm:items-end gap-1.5">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Stage</span>
                                @if($isApproved)
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs font-black px-3.5 py-1.5 rounded-xl shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>APPROVED & ACTIVE</span>
                                    </span>
                                @elseif($member->status === 'payment_pending')
                                    <span class="inline-flex items-center gap-1.5 bg-amber-500 text-slate-950 text-xs font-black px-3.5 py-1.5 rounded-xl shadow-xs">
                                        <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span>PAYMENT PENDING</span>
                                    </span>
                                @elseif($member->status === 'payment_confirmed')
                                    <span class="inline-flex items-center gap-1.5 bg-cyan-600 text-white text-xs font-black px-3.5 py-1.5 rounded-xl shadow-xs">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>PAYMENT VERIFIED • IN REVIEW</span>
                                    </span>
                                @elseif($member->status === 'rejected')
                                    <span class="inline-flex items-center gap-1.5 bg-rose-600 text-white text-xs font-black px-3.5 py-1.5 rounded-xl shadow-xs">
                                        <span>APPLICATION REJECTED</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-800 text-white text-xs font-black px-3.5 py-1.5 rounded-xl shadow-xs">
                                        <span>{{ strtoupper(str_replace('_', ' ', $member->status)) }}</span>
                                    </span>
                                @endif
                                <span class="text-[10px] text-slate-400 font-mono">Registered {{ $member->created_at->format('d M Y, H:i') }}</span>
                            </div>
                        </div>

                        <!-- 2. Interactive Application Pipeline Stepper -->
                        <div class="pt-6 relative z-10">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500 mb-4">Application Processing Roadmap</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                                <!-- Step 1 -->
                                <div class="p-3.5 rounded-2xl border bg-emerald-50 border-emerald-300 text-emerald-950 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-black flex items-center justify-center">✓</span>
                                        <span class="text-[10px] font-extrabold text-emerald-700 uppercase">Step 1</span>
                                    </div>
                                    <div class="font-bold text-xs">Application Submitted</div>
                                    <p class="text-[10px] text-emerald-800">Profile data registered</p>
                                </div>

                                <!-- Step 2 -->
                                <div class="p-3.5 rounded-2xl border {{ $isPaid ? 'bg-emerald-50 border-emerald-300 text-emerald-950' : 'bg-amber-50 border-amber-300 text-amber-950' }} space-y-1">
                                    <div class="flex items-center justify-between">
                                        @if($isPaid)
                                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-black flex items-center justify-center">✓</span>
                                            <span class="text-[10px] font-extrabold text-emerald-700 uppercase">Paid</span>
                                        @else
                                            <span class="w-6 h-6 rounded-full bg-amber-500 text-white text-xs font-black flex items-center justify-center">2</span>
                                            <span class="text-[10px] font-extrabold text-amber-700 uppercase">Action Req.</span>
                                        @endif
                                    </div>
                                    <div class="font-bold text-xs">Fee via Control No</div>
                                    <p class="text-[10px] {{ $isPaid ? 'text-emerald-800' : 'text-amber-800 font-semibold' }}">
                                        {{ $isPaid ? 'Confirmed' : 'Pending Payment' }}
                                    </p>
                                </div>

                                <!-- Step 3 -->
                                <div class="p-3.5 rounded-2xl border {{ in_array($member->status, ['payment_confirmed', 'approved']) ? 'bg-emerald-50 border-emerald-300 text-emerald-950' : 'bg-slate-50 border-slate-200 text-slate-600' }} space-y-1">
                                    <div class="flex items-center justify-between">
                                        @if(in_array($member->status, ['payment_confirmed', 'approved']))
                                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-black flex items-center justify-center">✓</span>
                                            <span class="text-[10px] font-extrabold text-emerald-700 uppercase">Verified</span>
                                        @else
                                            <span class="w-6 h-6 rounded-full bg-slate-300 text-slate-700 text-xs font-black flex items-center justify-center">3</span>
                                            <span class="text-[10px] font-extrabold text-slate-400 uppercase">Step 3</span>
                                        @endif
                                    </div>
                                    <div class="font-bold text-xs">Secretariat Review</div>
                                    <p class="text-[10px] text-slate-500">Licence & ID validation</p>
                                </div>

                                <!-- Step 4 -->
                                <div class="p-3.5 rounded-2xl border {{ $isApproved ? 'bg-emerald-50 border-emerald-300 text-emerald-950' : 'bg-slate-50 border-slate-200 text-slate-600' }} space-y-1">
                                    <div class="flex items-center justify-between">
                                        @if($isApproved)
                                            <span class="w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-black flex items-center justify-center">✓</span>
                                            <span class="text-[10px] font-extrabold text-emerald-700 uppercase">Issued</span>
                                        @else
                                            <span class="w-6 h-6 rounded-full bg-slate-300 text-slate-700 text-xs font-black flex items-center justify-center">4</span>
                                            <span class="text-[10px] font-extrabold text-slate-400 uppercase">Step 4</span>
                                        @endif
                                    </div>
                                    <div class="font-bold text-xs">Certificate & ID Card</div>
                                    <p class="text-[10px] text-slate-500">{{ $isApproved ? 'Ready to Download' : 'Unlocked upon Approval' }}</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- 3. Control Number Payment Card (If Not Paid or Pending) -->
                    @if($invoice && !$isPaid)
                        <div class="bg-gradient-to-br from-slate-900 via-slate-950 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white border-2 border-emerald-500/50 shadow-2xl space-y-6" x-data="{ copied: false }">
                            
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-4 border-b border-white/10">
                                <div>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3 py-1 rounded-md border border-emerald-700">
                                        Official Electronic Payment Bill
                                    </span>
                                    <h3 class="text-xl sm:text-2xl font-black font-heading text-white mt-1">
                                        Registration Fee Payment Details
                                    </h3>
                                    <p class="text-xs text-slate-300">
                                        Pay via Mobile Money (M-Pesa, Tigo Pesa, Airtel Money) or Bank with your unique Control Number.
                                    </p>
                                </div>

                                <div class="text-left sm:text-right bg-emerald-900/40 p-3.5 rounded-2xl border border-emerald-600/40">
                                    <span class="text-[10px] text-emerald-300 uppercase font-bold block">Amount to Pay</span>
                                    <strong class="text-2xl sm:text-3xl font-black text-emerald-400 font-mono">
                                        {{ number_format($invoice->amount) }} {{ $invoice->currency }}
                                    </strong>
                                </div>
                            </div>

                            <!-- Big Prominent Control Number Box -->
                            <div class="p-6 bg-slate-900/90 rounded-2xl border border-emerald-500/30 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
                                <div>
                                    <span class="text-xs uppercase font-extrabold text-slate-400 tracking-wider block">Official Payment Control Number</span>
                                    <div class="text-3xl sm:text-4xl font-black font-mono tracking-wider text-emerald-400 mt-1">
                                        {{ $invoice->control_number }}
                                    </div>
                                    <span class="text-[11px] text-slate-400 font-medium">Valid for all electronic banking and mobile wallets in Tanzania</span>
                                </div>

                                <button type="button" 
                                        @click="navigator.clipboard.writeText('{{ $invoice->control_number }}'); copied = true; setTimeout(() => copied = false, 3000)" 
                                        class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-6 py-3.5 rounded-xl text-xs uppercase tracking-wider flex items-center gap-2 transition shadow-lg shrink-0 cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                    <span x-text="copied ? 'Copied to Clipboard!' : 'Copy Control Number'"></span>
                                </button>
                            </div>

                            <!-- Mobile & Bank Payment Instructions -->
                            <div class="space-y-3">
                                <h4 class="text-xs font-black uppercase tracking-wider text-emerald-400">Step-by-Step Payment Methods</h4>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                    <!-- Vodacom M-Pesa -->
                                    <div class="p-4 bg-slate-900/60 rounded-xl border border-white/5 space-y-1.5">
                                        <div class="flex items-center gap-2 font-bold text-slate-200">
                                            <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                            <span>Vodacom M-Pesa</span>
                                        </div>
                                        <ol class="list-decimal list-inside text-slate-300 space-y-0.5 text-[11px]">
                                            <li>Dial <strong>*150*00#</strong></li>
                                            <li>Select <strong>4: Lipa kwa M-Pesa</strong></li>
                                            <li>Select <strong>1: Lipa Namba / Pay Bill</strong></li>
                                            <li>Enter Control Number: <strong class="text-emerald-400 font-mono">{{ $invoice->control_number }}</strong></li>
                                            <li>Enter Amount: <strong>{{ number_format($invoice->amount) }}</strong></li>
                                            <li>Enter PIN to confirm</li>
                                        </ol>
                                    </div>

                                    <!-- Tigo Pesa & Airtel Money -->
                                    <div class="p-4 bg-slate-900/60 rounded-xl border border-white/5 space-y-1.5">
                                        <div class="flex items-center gap-2 font-bold text-slate-200">
                                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-400"></span>
                                            <span>Tigo Pesa / Airtel Money / Halopesa</span>
                                        </div>
                                        <ol class="list-decimal list-inside text-slate-300 space-y-0.5 text-[11px]">
                                            <li>Dial <strong>*150*01#</strong> (Tigo) or <strong>*150*60#</strong> (Airtel)</li>
                                            <li>Select <strong>Lipa Bili / Pay Bill</strong></li>
                                            <li>Enter Control Number: <strong class="text-emerald-400 font-mono">{{ $invoice->control_number }}</strong></li>
                                            <li>Enter Amount: <strong>{{ number_format($invoice->amount) }}</strong></li>
                                            <li>Enter PIN to confirm</li>
                                        </ol>
                                    </div>

                                    <!-- CRDB & NMB Bank -->
                                    <div class="p-4 bg-slate-900/60 rounded-xl border border-white/5 space-y-1.5 md:col-span-2">
                                        <div class="flex items-center gap-2 font-bold text-slate-200">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                                            <span>Bank Deposit or Mobile Banking (NMB / CRDB / NBC / PBZ)</span>
                                        </div>
                                        <p class="text-[11px] text-slate-300">
                                            Open your bank mobile app (SimBanking, NMB Mkononi) or visit any branch/wakala. Choose <strong>Government / Bill Payments</strong> and enter Control Number <strong class="text-emerald-400 font-mono">{{ $invoice->control_number }}</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Payment Proof Form -->
                            <div class="pt-4 border-t border-white/10" x-data="{ openForm: false }">
                                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                    <div>
                                        <h4 class="text-xs font-bold text-white">Already Paid with this Control Number?</h4>
                                        <p class="text-[11px] text-slate-400">Submit your transaction reference number to expedite Secretariat verification.</p>
                                    </div>
                                    <button type="button" @click="openForm = !openForm" class="bg-slate-800 hover:bg-slate-700 text-emerald-300 border border-emerald-600/50 text-xs font-bold px-4 py-2.5 rounded-xl transition cursor-pointer shrink-0">
                                        <span x-text="openForm ? 'Hide Submission Form' : 'Submit Payment Reference &rarr;'"></span>
                                    </button>
                                </div>

                                <div x-show="openForm" class="mt-4 p-5 bg-slate-900 rounded-2xl border border-slate-700" style="display: none;">
                                    <form action="{{ route('track.submit_proof', $invoice->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-300 mb-1">Payment Method <span class="text-rose-400">*</span></label>
                                                <select name="payment_method" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-hidden focus:border-emerald-500">
                                                    <option value="mobile_money">Mobile Money (M-Pesa / Tigo / Airtel)</option>
                                                    <option value="bank_transfer">Bank Transfer / Wakala</option>
                                                    <option value="cash">Cash / Direct Deposit</option>
                                                    <option value="online_card">Card Payment</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-300 mb-1">Transaction Ref / SMS Code <span class="text-rose-400">*</span></label>
                                                <input type="text" name="transaction_reference" required placeholder="e.g. 9JA765TR89 or Ref No" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-hidden focus:border-emerald-500 font-mono">
                                            </div>

                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-300 mb-1">Amount Paid (TZS) <span class="text-rose-400">*</span></label>
                                                <input type="number" name="amount" required value="{{ $invoice->amount }}" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-white focus:outline-hidden focus:border-emerald-500">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-[11px] font-bold text-slate-300 mb-1">Upload Receipt / Screenshot (Optional)</label>
                                            <input type="file" name="proof_file" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-emerald-600 file:text-white file:font-semibold">
                                        </div>

                                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-3 rounded-xl text-xs transition shadow-md">
                                            Submit Transaction Confirmation to Secretariat
                                        </button>
                                    </form>
                                </div>
                            </div>

                        </div>
                    @endif

                    <!-- 4. Certificate & Digital ID Card Section (When Approved) -->
                    @if($isApproved)
                        <div class="bg-gradient-to-r from-emerald-900 via-teal-900 to-slate-900 rounded-3xl p-6 sm:p-8 text-white shadow-2xl border border-emerald-500/40 space-y-6">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 pb-4 border-b border-white/10">
                                <div>
                                    <div class="inline-flex items-center gap-1.5 bg-emerald-950 px-3 py-1 rounded-md border border-emerald-700 text-[10px] font-black uppercase tracking-widest text-emerald-300">
                                        <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        <span>Official Credentials Issued & Verified</span>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-black font-heading text-white mt-1.5">
                                        Download Official Certificate & Digital ID Card
                                    </h3>
                                    <p class="text-xs text-slate-300 mt-0.5">
                                        Membership Number: <strong class="text-emerald-300 font-mono">{{ $member->membership_number }}</strong> • Validity: {{ $member->expiry_date ? $member->expiry_date->format('d M Y') : 'Active' }} • <span class="text-emerald-400 font-semibold">No login required to download.</span>
                                    </p>
                                </div>

                                <div class="shrink-0 flex items-center gap-2">
                                    <a href="{{ route('verify.membership', $member->membership_number) }}" class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-emerald-300 border border-emerald-500/40 px-3.5 py-2 rounded-xl text-xs font-bold transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <span>Public Registry Record</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Download Action Cards Grid -->
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- 1. Certificate of Membership -->
                                <div class="bg-slate-900/80 p-4 rounded-2xl border border-emerald-500/30 flex flex-col justify-between space-y-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-400 bg-emerald-950 px-2 py-0.5 rounded border border-emerald-800">Official</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $member->membershipCertificate?->certificate_number ?? 'CERT-MEM' }}</span>
                                        </div>
                                        <h4 class="font-bold text-xs text-white">Certificate of Membership</h4>
                                        <p class="text-[11px] text-slate-300">High-resolution authentic PDF with cryptographic QR verification stamp.</p>
                                    </div>
                                    <a href="{{ route('public.certificate.download', $member->membershipCertificate?->certificate_number ?? $member->membership_number) }}" 
                                       class="w-full bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Download Certificate (PDF)</span>
                                    </a>
                                </div>

                                <!-- 2. Digital ID Card (CR80 Standard PVC) -->
                                <div class="bg-slate-900/80 p-4 rounded-2xl border border-emerald-500/30 flex flex-col justify-between space-y-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] font-black uppercase tracking-wider text-cyan-400 bg-cyan-950 px-2 py-0.5 rounded border border-cyan-800">Smart Badge</span>
                                            <span class="text-[10px] text-slate-400 font-mono">CR80 PVC</span>
                                        </div>
                                        <h4 class="font-bold text-xs text-white">Digital Driver ID Card</h4>
                                        <p class="text-[11px] text-slate-300">Dual-sided digital member smart card with photo, barcode & QR lookup.</p>
                                    </div>
                                    <a href="{{ route('public.card.download', $member->membership_number) }}" 
                                       class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-950 font-black px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition shadow-md">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        <span>Download ID Card (PDF)</span>
                                    </a>
                                </div>

                                <!-- 3. Printable ID Sheet (A4 Format) -->
                                <div class="bg-slate-900/80 p-4 rounded-2xl border border-emerald-500/30 flex flex-col justify-between space-y-3">
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] font-black uppercase tracking-wider text-amber-400 bg-amber-950 px-2 py-0.5 rounded border border-amber-800">Print Ready</span>
                                            <span class="text-[10px] text-slate-400 font-mono">A4 Sheet</span>
                                        </div>
                                        <h4 class="font-bold text-xs text-white">Printable A4 ID Sheet</h4>
                                        <p class="text-[11px] text-slate-300">Layout ready for direct color printing, laminating, and cutting.</p>
                                    </div>
                                    <a href="{{ route('public.card.download', ['number' => $member->membership_number, 'format' => 'a4']) }}" 
                                       class="w-full bg-slate-800 hover:bg-slate-700 text-white border border-slate-600 font-bold px-4 py-2.5 rounded-xl text-xs flex items-center justify-center gap-2 transition">
                                        <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span>Download A4 Print Sheet</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Certificate Notice Box -->
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 text-xs text-slate-600 flex items-start gap-3">
                            <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-200 font-bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <div class="space-y-0.5">
                                <strong class="text-slate-900 font-bold block">How to Obtain Your Official Membership Certificate & Smart ID Card:</strong>
                                <p>Once your registration fee is paid using the official <strong>Control Number</strong> ({{ $invoice->control_number ?? 'Generated upon billing' }}) and your driving documents are verified by the Secretariat, your verified Certificate of Membership and Digital ID Card will be immediately unlocked for download.</p>
                            </div>
                        </div>
                    @endif

                </div>

            @else
                <!-- Not Found State -->
                <div class="p-8 sm:p-10 bg-white border-2 border-slate-200 rounded-3xl text-center space-y-4 shadow-sm">
                    <div class="w-16 h-16 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mx-auto border border-rose-200">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-xl font-black text-slate-900 font-heading">No Application or Member Record Found</h3>
                        <p class="text-xs text-slate-500 max-w-md mx-auto leading-relaxed">
                            We could not find any registration record matching "<strong>{{ $searchQuery }}</strong>". Please double check your Driving Licence Number or Mobile Phone Number.
                        </p>
                    </div>

                    <div class="pt-2 flex flex-wrap justify-center gap-3">
                        <a href="{{ route('track.application') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl text-xs transition">
                            Clear Search
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl text-xs transition shadow-md shadow-emerald-600/20">
                            Start Online Registration &rarr;
                        </a>
                    </div>
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
