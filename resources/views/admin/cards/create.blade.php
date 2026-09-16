@extends('layouts.admin')

@section('title', 'Create & Issue Member ID Card')

@push('styles')
<style>
    /* 3D Flip Card Container */
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

    /* Standard CR80 aspect ratio in CSS (85.6mm / 53.98mm = ~1.585) */
    .id-card-preview {
        width: 100%;
        max-width: 440px;
        aspect-ratio: 85.6 / 53.98;
        border-radius: 16px;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="idCardStudio()">

    <!-- Top Navigation Breadcrumb -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.cards.index') }}" class="hover:text-emerald-600 transition">ID Cards</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">ID Card Creator Studio</span>
        </div>
        <a href="{{ route('admin.cards.index') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Cards List</span>
        </a>
    </div>

    <!-- Main Grid: Studio Controls on Left, Live 3D Preview on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Form Controls (7 cols) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
                <div>
                    <h1 class="text-xl font-black font-heading text-slate-900 tracking-tight">Create / Issue Member ID Card</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Select an approved member and customize card parameters with live preview.</p>
                </div>

                @if ($errors->any())
                    <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-xs text-rose-700 space-y-1">
                        <strong class="font-bold block">Please correct the errors below:</strong>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.cards.store') }}" class="space-y-6" @submit="isSubmitting = true">
                    @csrf

                    <!-- 1. Member Selection -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Select Member <span class="text-rose-500">*</span>
                        </label>
                        <select name="member_id" id="member_id" x-model="selectedMemberId" @change="onMemberChange()" required class="w-full py-3 px-4 text-xs sm:text-sm rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition font-medium">
                            <option value="">-- Choose an approved member --</option>
                            @foreach($approvedMembers as $member)
                                <option value="{{ $member->id }}" 
                                    data-name="{{ $member->full_name }}"
                                    data-number="{{ $member->membership_number ?? 'PENDING' }}"
                                    data-category="{{ $member->category->name }}"
                                    data-region="{{ $member->region?->name ?? 'Tanzania' }}"
                                    data-district="{{ $member->district?->name ?? '' }}"
                                    data-photo="{{ $member->passport_photo_path ? asset('storage/' . $member->passport_photo_path) : '' }}"
                                    data-expiry="{{ $member->expiry_date ? $member->expiry_date->format('Y-m-d') : now()->addYear()->format('Y-m-d') }}"
                                    data-card-number="{{ $member->card?->card_number ?? ('CARD-' . ($member->membership_number ?: 'NEW')) }}"
                                    {{ (old('member_id', $selectedMember?->id) == $member->id) ? 'selected' : '' }}>
                                    {{ $member->full_name }} ({{ $member->membership_number ?? 'No Number' }} - {{ $member->category->name }})
                                </option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-slate-400">Only approved members with verified accounts appear in this list.</p>
                    </div>

                    <!-- 2. Theme Picker -->
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                            Card Visual Theme
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            <label class="cursor-pointer border-2 rounded-2xl p-3 text-center transition"
                                :class="selectedTheme === 'emerald' ? 'border-emerald-600 bg-emerald-50/50 shadow-xs ring-2 ring-emerald-600/20' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="theme" value="emerald" x-model="selectedTheme" class="sr-only">
                                <div class="w-full h-8 rounded-lg bg-gradient-to-r from-emerald-950 via-teal-900 to-slate-900 mb-2 border border-emerald-500/30"></div>
                                <span class="text-xs font-bold text-slate-900 block">Emerald</span>
                                <span class="text-[10px] text-slate-400 block">Official TEVDA</span>
                            </label>

                            <label class="cursor-pointer border-2 rounded-2xl p-3 text-center transition"
                                :class="selectedTheme === 'midnight' ? 'border-amber-500 bg-amber-50/50 shadow-xs ring-2 ring-amber-500/20' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="theme" value="midnight" x-model="selectedTheme" class="sr-only">
                                <div class="w-full h-8 rounded-lg bg-gradient-to-r from-slate-950 via-slate-900 to-amber-950 mb-2 border border-amber-500/30"></div>
                                <span class="text-xs font-bold text-slate-900 block">Midnight</span>
                                <span class="text-[10px] text-slate-400 block">Executive Gold</span>
                            </label>

                            <label class="cursor-pointer border-2 rounded-2xl p-3 text-center transition"
                                :class="selectedTheme === 'cyan' ? 'border-cyan-500 bg-cyan-50/50 shadow-xs ring-2 ring-cyan-500/20' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="theme" value="cyan" x-model="selectedTheme" class="sr-only">
                                <div class="w-full h-8 rounded-lg bg-gradient-to-r from-cyan-950 via-teal-950 to-slate-900 mb-2 border border-cyan-500/30"></div>
                                <span class="text-xs font-bold text-slate-900 block">Electric Cyan</span>
                                <span class="text-[10px] text-slate-400 block">High-Tech EV</span>
                            </label>

                            <label class="cursor-pointer border-2 rounded-2xl p-3 text-center transition"
                                :class="selectedTheme === 'clean' ? 'border-slate-400 bg-slate-50 shadow-xs ring-2 ring-slate-400/20' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="theme" value="clean" x-model="selectedTheme" class="sr-only">
                                <div class="w-full h-8 rounded-lg bg-gradient-to-r from-slate-100 via-white to-slate-200 mb-2 border border-slate-300"></div>
                                <span class="text-xs font-bold text-slate-900 block">Daylight</span>
                                <span class="text-[10px] text-slate-400 block">Clean Light</span>
                            </label>
                        </div>
                    </div>

                    <!-- 3. Card Number & Motto -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Card ID / Number
                            </label>
                            <input type="text" name="card_number" x-model="cardNumber" placeholder="e.g. CARD-TEVDA-2026-0001" class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white font-mono font-bold transition">
                            <p class="text-[10px] text-slate-400 mt-1">Leave empty to auto-assign based on membership #</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Slogan / Card Motto
                            </label>
                            <input type="text" name="motto" x-model="cardMotto" placeholder="SMART DRIVERS SMART MOBILITY" class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition">
                        </div>
                    </div>

                    <!-- 4. Validity Dates -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Issue Date <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="issue_date" x-model="issueDate" required class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition">
                        </div>

                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                                Expiration Date
                            </label>
                            <input type="date" name="expiry_date" x-model="expiryDate" class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition">
                            <p class="text-[10px] text-slate-400 mt-1">Defaults to 1 year validity from issue date</p>
                        </div>
                    </div>

                    <!-- 5. Admin Notes -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                            Internal Administrative Notes (Optional)
                        </label>
                        <textarea name="notes" rows="2" placeholder="e.g. Issued upon verified annual registration renewal..." class="w-full py-2 px-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <span class="text-xs text-slate-500">Live preview updates automatically on changes</span>
                        <button type="submit" :disabled="!selectedMemberId || isSubmitting" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-300 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-md transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span x-text="isSubmitting ? 'Generating Card...' : 'Issue & Activate ID Card'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Right: Interactive Live 3D Preview (5 cols) -->
        <div class="lg:col-span-5 space-y-4 lg:sticky lg:top-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold font-heading text-slate-900">Interactive 3D Preview</h2>
                        <p class="text-[11px] text-slate-400">CR80 Plastic Card Spec (85.6mm × 54mm)</p>
                    </div>
                    <button type="button" @click="isFlipped = !isFlipped" class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-3 py-1.5 rounded-xl border border-emerald-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span x-text="isFlipped ? 'View Front Side' : 'Flip to Back Side'"></span>
                    </button>
                </div>

                <!-- 3D Card Container -->
                <div class="perspective-container py-2 flex justify-center">
                    <div class="id-card-preview card-flip-inner relative shadow-2xl cursor-pointer" :class="{ 'is-flipped': isFlipped }" @click="isFlipped = !isFlipped" title="Click to flip card">
                        
                        <!-- ==================== FRONT SIDE ==================== -->
                        <div class="card-face absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden border"
                            :class="{
                                'bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white border-emerald-500/40': selectedTheme === 'emerald',
                                'bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 text-white border-amber-500/40': selectedTheme === 'midnight',
                                'bg-gradient-to-br from-slate-950 via-cyan-950 to-teal-950 text-white border-cyan-500/40': selectedTheme === 'cyan',
                                'bg-gradient-to-br from-slate-50 via-white to-slate-100 text-slate-900 border-slate-300': selectedTheme === 'clean'
                            }">
                            
                            <!-- Header -->
                            <div class="flex items-center justify-between pb-2 border-b"
                                :class="selectedTheme === 'clean' ? 'border-slate-200' : 'border-white/10'">
                                <div class="flex items-center gap-2">
                                    @if(\App\Models\Setting::hasCustomLogo())
                                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" alt="Logo" class="h-6 max-w-[32px] object-contain">
                                    @else
                                        <div class="w-6 h-6 rounded-md bg-emerald-600 text-white font-black text-[9px] flex items-center justify-center">TEV</div>
                                    @endif
                                    <div>
                                        <span class="text-[11px] font-black tracking-tight block font-heading leading-tight"
                                            :class="selectedTheme === 'clean' ? 'text-emerald-800' : 'text-white'">TEVDA</span>
                                        <span class="text-[6.5px] uppercase font-bold tracking-wider block"
                                            :class="{
                                                'text-emerald-400': selectedTheme === 'emerald',
                                                'text-amber-400': selectedTheme === 'midnight',
                                                'text-cyan-400': selectedTheme === 'cyan',
                                                'text-emerald-600': selectedTheme === 'clean'
                                            }">Tanzania EV Drivers Association</span>
                                    </div>
                                </div>
                                <span class="text-[7.5px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border"
                                    :class="{
                                        'bg-emerald-900/70 text-emerald-300 border-emerald-700': selectedTheme === 'emerald',
                                        'bg-amber-900/70 text-amber-300 border-amber-700': selectedTheme === 'midnight',
                                        'bg-cyan-900/70 text-cyan-300 border-cyan-700': selectedTheme === 'cyan',
                                        'bg-slate-200 text-slate-800 border-slate-300': selectedTheme === 'clean'
                                    }" x-text="memberCategory">
                                </span>
                            </div>

                            <!-- Body: Photo + Info + QR -->
                            <div class="grid grid-cols-12 gap-3 items-center my-auto">
                                <!-- Photo -->
                                <div class="col-span-3">
                                    <template x-if="memberPhoto">
                                        <img :src="memberPhoto" alt="Photo" class="w-14 h-18 sm:w-16 sm:h-20 object-cover rounded-xl border-2 shadow-xs"
                                            :class="selectedTheme === 'clean' ? 'border-emerald-600' : 'border-emerald-500/50'">
                                    </template>
                                    <template x-if="!memberPhoto">
                                        <div class="w-14 h-18 sm:w-16 sm:h-20 rounded-xl border flex flex-col items-center justify-center text-[8px] font-bold"
                                            :class="selectedTheme === 'clean' ? 'bg-slate-200 border-slate-300 text-slate-500' : 'bg-slate-800/80 border-slate-700 text-slate-400'">
                                            <svg class="w-5 h-5 mb-0.5 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            <span>PHOTO</span>
                                        </div>
                                    </template>
                                </div>

                                <!-- Details -->
                                <div class="col-span-6 space-y-1">
                                    <div>
                                        <span class="text-[7.5px] uppercase text-slate-400 block font-semibold leading-none">Member Name</span>
                                        <strong class="text-xs sm:text-sm font-black block truncate"
                                            :class="selectedTheme === 'clean' ? 'text-slate-900' : 'text-white'" x-text="memberName"></strong>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] uppercase text-slate-400 block font-semibold leading-none">Member Number</span>
                                        <span class="font-mono font-bold text-xs block"
                                            :class="{
                                                'text-emerald-400': selectedTheme === 'emerald',
                                                'text-amber-400': selectedTheme === 'midnight',
                                                'text-cyan-400': selectedTheme === 'cyan',
                                                'text-emerald-700': selectedTheme === 'clean'
                                            }" x-text="memberNumber"></span>
                                    </div>
                                    <div>
                                        <span class="text-[7.5px] uppercase text-slate-400 block font-semibold leading-none">Region / Valid Thru</span>
                                        <span class="text-[10px] font-semibold block"
                                            :class="selectedTheme === 'clean' ? 'text-slate-600' : 'text-slate-300'">
                                            <span x-text="memberRegion"></span> • <strong class="text-amber-400" x-text="formatExpiry(expiryDate)"></strong>
                                        </span>
                                    </div>
                                </div>

                                <!-- QR -->
                                <div class="col-span-3 flex flex-col items-end">
                                    <div class="bg-white p-1 rounded-lg shadow-xs">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=https://tevda.or.tz/verify" alt="QR" class="w-10 h-10 sm:w-11 sm:h-11">
                                    </div>
                                    <span class="text-[5.5px] uppercase tracking-wider text-slate-400 mt-1">Scan to Verify</span>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div class="pt-2 border-t flex items-center justify-between"
                                :class="selectedTheme === 'clean' ? 'border-slate-200' : 'border-white/10'">
                                <span class="text-[7.5px] font-bold uppercase tracking-wider"
                                    :class="{
                                        'text-emerald-400': selectedTheme === 'emerald',
                                        'text-amber-400': selectedTheme === 'midnight',
                                        'text-cyan-400': selectedTheme === 'cyan',
                                        'text-emerald-800': selectedTheme === 'clean'
                                    }" x-text="cardMotto"></span>
                                <span class="text-[7.5px] text-slate-400 font-bold uppercase">WWW.TEVDA.OR.TZ</span>
                            </div>
                        </div>

                        <!-- ==================== BACK SIDE ==================== -->
                        <div class="card-face card-face-back absolute inset-0 rounded-2xl p-4 sm:p-5 flex flex-col justify-between overflow-hidden border"
                            :class="{
                                'bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 text-white border-emerald-500/40': selectedTheme === 'emerald',
                                'bg-gradient-to-br from-slate-950 via-slate-900 to-amber-950 text-white border-amber-500/40': selectedTheme === 'midnight',
                                'bg-gradient-to-br from-slate-950 via-cyan-950 to-teal-950 text-white border-cyan-500/40': selectedTheme === 'cyan',
                                'bg-gradient-to-br from-slate-50 via-white to-slate-100 text-slate-900 border-slate-300': selectedTheme === 'clean'
                            }">
                            
                            <!-- Magnetic Bar -->
                            <div class="h-6 bg-slate-950 -mx-5 -mt-5 px-5 flex items-center justify-between border-b border-white/5">
                                <span class="font-mono text-[7.5px] text-slate-400 tracking-wider" x-text="cardNumber || ('CARD-' + memberNumber)"></span>
                                <span class="text-[6.5px] text-emerald-400 font-bold uppercase">TEVDA DIGITAL BADGE</span>
                            </div>

                            <!-- Terms -->
                            <div class="text-[7.5px] leading-tight text-slate-400 text-justify my-1">
                                This official card certifies authorized membership in TEVDA. Non-transferable and must be presented upon inspection. Subject to association bylaws.
                            </div>

                            <!-- Back Grid -->
                            <div class="grid grid-cols-2 gap-3 items-end">
                                <!-- Signatory -->
                                <div class="space-y-0.5">
                                    <span class="text-[6.5px] uppercase text-slate-400 block">Authorized Signature</span>
                                    <div class="border-b border-slate-600 pb-0.5">
                                        <span class="font-serif italic text-xs text-emerald-400">Charles Mwansasu</span>
                                    </div>
                                    <span class="text-[7px] font-bold block" :class="selectedTheme === 'clean' ? 'text-slate-900' : 'text-white'">Dr. Charles Mwansasu</span>
                                    <span class="text-[6.5px] text-slate-400 block">Founding Chairperson • TEVDA</span>
                                </div>

                                <!-- Contact Info -->
                                <div class="p-2 rounded-xl text-[7px] leading-tight space-y-0.5"
                                    :class="selectedTheme === 'clean' ? 'bg-slate-200/80 text-slate-700' : 'bg-slate-900/90 text-slate-300 border border-white/5'">
                                    <strong class="text-emerald-400 block font-bold text-[7.5px]">TEVDA HQ & HELPLINE</strong>
                                    <p>Dar es Salaam, Tanzania</p>
                                    <p class="font-mono">+255 700 000 000</p>
                                    <p>info@tevda.or.tz</p>
                                </div>
                            </div>

                            <!-- Return Notice -->
                            <div class="text-[6.5px] text-center text-slate-500 uppercase tracking-wider pt-1 border-t border-white/5">
                                Property of TEVDA. If found, return to nearest TEVDA branch.
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-center">
                    <p class="text-[11px] text-slate-500">
                        <span class="font-bold text-slate-700">Tip:</span> Click directly on the card to flip between Front and Back views.
                    </p>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
function idCardStudio() {
    return {
        selectedMemberId: '{{ old('member_id', $selectedMember?->id ?? '') }}',
        selectedTheme: '{{ old('theme', 'emerald') }}',
        cardNumber: '{{ old('card_number', $selectedMember ? ($selectedMember->card?->card_number ?? ('CARD-' . $selectedMember->membership_number)) : '') }}',
        cardMotto: '{{ old('motto', 'SMART DRIVERS SMART MOBILITY') }}',
        issueDate: '{{ old('issue_date', date('Y-m-d')) }}',
        expiryDate: '{{ old('expiry_date', date('Y-m-d', strtotime('+1 year'))) }}',
        
        memberName: '{{ $selectedMember?->full_name ?? 'Select Member...' }}',
        memberNumber: '{{ $selectedMember?->membership_number ?? 'TEVDA-XXXX-0000' }}',
        memberCategory: '{{ $selectedMember?->category?->name ?? 'Category Tier' }}',
        memberRegion: '{{ $selectedMember?->region?->name ?? 'Tanzania' }}',
        memberPhoto: '{{ $selectedMember?->passport_photo_path ? asset('storage/' . $selectedMember->passport_photo_path) : '' }}',
        
        isFlipped: false,
        isSubmitting: false,

        init() {
            if (this.selectedMemberId) {
                this.onMemberChange();
            }
        },

        onMemberChange() {
            const select = document.getElementById('member_id');
            const selectedOpt = select.options[select.selectedIndex];
            
            if (selectedOpt && selectedOpt.value) {
                this.memberName = selectedOpt.dataset.name || 'Member Name';
                this.memberNumber = selectedOpt.dataset.number || 'PENDING';
                this.memberCategory = selectedOpt.dataset.category || 'Member';
                this.memberRegion = selectedOpt.dataset.region || 'Tanzania';
                this.memberPhoto = selectedOpt.dataset.photo || '';
                
                if (!this.cardNumber || this.cardNumber.startsWith('CARD-')) {
                    this.cardNumber = selectedOpt.dataset.cardNumber || ('CARD-' + this.memberNumber);
                }
                if (selectedOpt.dataset.expiry) {
                    this.expiryDate = selectedOpt.dataset.expiry;
                }
            } else {
                this.memberName = 'Select Member...';
                this.memberNumber = 'TEVDA-XXXX-0000';
                this.memberCategory = 'Category Tier';
                this.memberRegion = 'Tanzania';
                this.memberPhoto = '';
            }
        },

        formatExpiry(dateStr) {
            if (!dateStr) return 'ACTIVE';
            const d = new Date(dateStr);
            if (isNaN(d.getTime())) return 'ACTIVE';
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            return `${month}/${year}`;
        }
    };
}
</script>
@endpush
