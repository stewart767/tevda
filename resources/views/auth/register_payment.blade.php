@extends('layouts.app')

@section('title', 'Lipa Ada ya Usajili na Cheti — TEVDA')

@section('content')
<!-- Hero Header -->
<div class="relative bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-950 text-white py-10 lg:py-12 overflow-hidden border-b border-emerald-900/40">
    <div class="absolute -left-20 -top-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-900/80 border border-emerald-700/60 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest text-emerald-300 mb-3 shadow-xs">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Hatua ya 2 ya 3 • Malipo ya Ada ya Usajili & Cheti</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight text-white mb-2">
            Lipa Ada ya Usajili na Cheti (TZS 15,000)
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
            Kamilisha malipo ili kufungua fomu ya mwisho ya maelezo ya leseni na chombo (EV) na kutoa rasmi <strong>Cheti chako cha Uanachama</strong>.
        </p>

        <!-- Stepper Indicator -->
        <div class="mt-6 max-w-xl mx-auto grid grid-cols-3 gap-2 text-xs font-bold">
            <div class="p-2.5 rounded-xl bg-emerald-500/20 border border-emerald-400/50 text-emerald-300 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                <span>1. Taarifa za Awali</span>
            </div>
            <div class="p-2.5 rounded-xl bg-emerald-500 text-slate-950 font-black flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30">
                <span class="w-5 h-5 rounded-full bg-slate-950 text-emerald-400 flex items-center justify-center text-[10px] font-black">2</span>
                <span>2. Malipo (15,000 TZS)</span>
            </div>
            <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-400 flex items-center justify-center gap-2">
                <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center text-[10px]">3</span>
                <span>3. Leseni & Cheti</span>
            </div>
        </div>
    </div>
</div>

<div class="py-10 bg-slate-100 min-h-[80vh]" x-data="{
    activeTab: 'push',
    selectedProvider: 'mpesa',
    phoneNumber: '{{ $member->phone ?? (Auth::user()->phone ?? '') }}',
    isProcessing: false,
    processingStep: 1,
    copied: false,
    copyControl() {
        navigator.clipboard.writeText('{{ $invoice->control_number }}');
        this.copied = true;
        setTimeout(() => this.copied = false, 2500);
    },
    startPush() {
        if (!this.phoneNumber) {
            alert('Tafadhali weka namba ya simu ya kulipia.');
            return;
        }
        this.isProcessing = true;
        this.processingStep = 1;

        // Simulate real-time USSD push prompt
        setTimeout(() => {
            this.processingStep = 2;
        }, 1800);
    }
}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @include('partials.alerts')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

            <!-- Left Column: Invoice Details & Control Number Card -->
            <div class="lg:col-span-5 space-y-5">
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-xl space-y-5">
                    
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <span class="text-[10px] font-mono uppercase tracking-wider text-slate-400 block">Namba ya Ankara (Invoice)</span>
                            <span class="font-bold text-slate-800 font-mono text-sm">{{ $invoice->invoice_number }}</span>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase bg-amber-100 text-amber-800 border border-amber-200">
                            {{ $invoice->status }}
                        </span>
                    </div>

                    <!-- Highlighted Control Number -->
                    <div class="p-4 bg-gradient-to-br from-slate-900 to-emerald-950 text-white rounded-2xl border border-emerald-900/50 shadow-md">
                        <span class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest block mb-1">Namba ya Malipo (Control Number)</span>
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-2xl font-black font-mono tracking-wider text-white select-all">{{ $invoice->control_number }}</span>
                            <button type="button" 
                                    @click="copyControl()" 
                                    class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition flex items-center gap-1.5 cursor-pointer">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                <span x-text="copied ? 'Imenakiliwa!' : 'Nakili'"></span>
                            </button>
                        </div>
                        <span class="text-[10px] text-slate-400 block mt-1.5">Inakubalika M-Pesa, Tigo Pesa, Airtel Money, Halopesa & Benki.</span>
                    </div>

                    <!-- Fee Breakdown -->
                    <div class="space-y-2.5 pt-1 text-xs">
                        <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400 block">Mchanganuo wa Malipo</span>
                        
                        <div class="flex justify-between items-center text-slate-700 py-1 border-b border-slate-100">
                            <span>1. Ada ya kuendelea na fomu ya usajili:</span>
                            <span class="font-bold text-slate-900 font-mono">TZS 5,000</span>
                        </div>
                        
                        <div class="flex justify-between items-center text-slate-700 py-1 border-b border-slate-100">
                            <span>2. Ada ya Cheti Rasmi cha Uanachama:</span>
                            <span class="font-bold text-slate-900 font-mono">TZS 10,000</span>
                        </div>

                        <div class="flex justify-between items-center pt-2 text-slate-900 font-black text-sm">
                            <span>Jumla Inayotakiwa:</span>
                            <span class="font-mono text-emerald-700 text-base">TZS 15,000</span>
                        </div>
                    </div>

                    <!-- Member info snippet -->
                    <div class="p-3 bg-slate-50 rounded-xl border border-slate-200/80 text-[11px] space-y-1 text-slate-600">
                        <div><strong class="text-slate-800">Mwanachama:</strong> {{ $invoice->member->full_name ?? Auth::user()->name }}</div>
                        <div><strong class="text-slate-800">Simu:</strong> <span class="font-mono">{{ $invoice->member->phone ?? Auth::user()->phone }}</span></div>
                        <div><strong class="text-slate-800">Kundi:</strong> {{ $invoice->member->category->name ?? 'Commercial EV Member' }}</div>
                    </div>

                </div>
            </div>

            <!-- Right Column: Interactive Payment Methods Tabs -->
            <div class="lg:col-span-7">
                <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-xl space-y-6">

                    <!-- Tabs Header -->
                    <div class="flex border-b border-slate-200 text-xs font-bold gap-2 overflow-x-auto pb-1">
                        <button type="button" 
                                @click="activeTab = 'push'" 
                                class="px-4 py-2.5 rounded-xl transition cursor-pointer flex items-center gap-2 whitespace-nowrap"
                                :class="activeTab === 'push' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            <span>1. Lipa kwa Simu (Push STK)</span>
                        </button>

                        <button type="button" 
                                @click="activeTab = 'ussd'" 
                                class="px-4 py-2.5 rounded-xl transition cursor-pointer flex items-center gap-2 whitespace-nowrap"
                                :class="activeTab === 'ussd' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span>2. Menyu ya USSD</span>
                        </button>

                        <button type="button" 
                                @click="activeTab = 'manual'" 
                                class="px-4 py-2.5 rounded-xl transition cursor-pointer flex items-center gap-2 whitespace-nowrap"
                                :class="activeTab === 'manual' ? 'bg-emerald-600 text-white shadow-xs' : 'text-slate-600 hover:text-emerald-700 hover:bg-slate-100'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>3. Weka Muamala (Ref)</span>
                        </button>
                    </div>

                    <!-- TAB 1: Instant Mobile Money Push STK -->
                    <div x-show="activeTab === 'push'" class="space-y-5">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 font-heading">Lipa Papo Hapo Kupitia Simu Yako</h3>
                            <p class="text-xs text-slate-500">Chagua mtandao na utume ombi la malipo. Mfumo utakutumia ujumbe wa kuweka PIN kwenye simu yako mara moja.</p>
                        </div>

                        <!-- Provider selection cards -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <label class="p-3 rounded-2xl border-2 text-center cursor-pointer transition flex flex-col items-center justify-center gap-1.5"
                                   :class="selectedProvider === 'mpesa' ? 'border-emerald-600 bg-emerald-50/70 shadow-xs' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="provider" value="mpesa" x-model="selectedProvider" class="hidden">
                                <span class="w-8 h-8 rounded-full bg-rose-600 text-white font-black text-[11px] flex items-center justify-center shadow-xs">M</span>
                                <span class="text-xs font-bold text-slate-800">M-Pesa</span>
                            </label>

                            <label class="p-3 rounded-2xl border-2 text-center cursor-pointer transition flex flex-col items-center justify-center gap-1.5"
                                   :class="selectedProvider === 'tigopesa' ? 'border-emerald-600 bg-emerald-50/70 shadow-xs' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="provider" value="tigopesa" x-model="selectedProvider" class="hidden">
                                <span class="w-8 h-8 rounded-full bg-blue-600 text-white font-black text-[11px] flex items-center justify-center shadow-xs">T</span>
                                <span class="text-xs font-bold text-slate-800">Tigo Pesa</span>
                            </label>

                            <label class="p-3 rounded-2xl border-2 text-center cursor-pointer transition flex flex-col items-center justify-center gap-1.5"
                                   :class="selectedProvider === 'airtel' ? 'border-emerald-600 bg-emerald-50/70 shadow-xs' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="provider" value="airtel" x-model="selectedProvider" class="hidden">
                                <span class="w-8 h-8 rounded-full bg-red-600 text-white font-black text-[11px] flex items-center justify-center shadow-xs">A</span>
                                <span class="text-xs font-bold text-slate-800">Airtel Money</span>
                            </label>

                            <label class="p-3 rounded-2xl border-2 text-center cursor-pointer transition flex flex-col items-center justify-center gap-1.5"
                                   :class="selectedProvider === 'halopesa' ? 'border-emerald-600 bg-emerald-50/70 shadow-xs' : 'border-slate-200 hover:border-slate-300'">
                                <input type="radio" name="provider" value="halopesa" x-model="selectedProvider" class="hidden">
                                <span class="w-8 h-8 rounded-full bg-amber-500 text-white font-black text-[11px] flex items-center justify-center shadow-xs">H</span>
                                <span class="text-xs font-bold text-slate-800">Halopesa</span>
                            </label>
                        </div>

                        <!-- Phone number input -->
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Namba ya Simu ya Kulipia (M-Pesa / Tigo / Airtel) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" x-model="phoneNumber" placeholder="e.g. 0757 700 401" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm font-mono focus:bg-white focus:border-emerald-500 outline-none">
                            <span class="text-[11px] text-slate-400 block mt-1">Ujumbe wa kuweka PIN utatumwa kwenye namba hii.</span>
                        </div>

                        <!-- Submit Button -->
                        <button type="button" 
                                @click="startPush()" 
                                class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black py-4 px-6 rounded-2xl text-sm transition-all duration-200 shadow-xl shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Lipa Sasa kwa USSD Push (TZS 15,000) &rarr;</span>
                        </button>
                    </div>

                    <!-- TAB 2: USSD Manual Instructions -->
                    <div x-show="activeTab === 'ussd'" class="space-y-4" style="display: none;">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 font-heading">Maelekezo ya Kulipa kwa Simu Yako (Menyu ya USSD)</h3>
                            <p class="text-xs text-slate-500">Tumia Control Number: <strong class="text-emerald-700 font-mono">{{ $invoice->control_number }}</strong> kulipia TZS 15,000 kupitia mtandao wako:</p>
                        </div>

                        <div class="space-y-3 text-xs">
                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                                <span class="font-bold text-rose-700 block">Vodacom M-Pesa:</span>
                                <p class="text-slate-600">Piga <strong>*150*00#</strong> &rarr; Chagua 4. Lipa kwa M-Pesa &rarr; Chagua 4. Weka Namba ya Kampuni (<strong>400200</strong>) &rarr; Weka Namba ya Kumbukumbu (<strong>{{ $invoice->control_number }}</strong>) &rarr; Weka Kiasi (<strong>15,000</strong>) &rarr; Weka PIN.</p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                                <span class="font-bold text-blue-700 block">Tigo Pesa:</span>
                                <p class="text-slate-600">Piga <strong>*150*01#</strong> &rarr; Chagua 4. Lipa Bili &rarr; Weka Namba ya Kampuni &rarr; Weka Kumbukumbu Namba (<strong>{{ $invoice->control_number }}</strong>) &rarr; Weka Kiasi (<strong>15,000</strong>) &rarr; Weka PIN.</p>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-1">
                                <span class="font-bold text-red-700 block">Airtel Money:</span>
                                <p class="text-slate-600">Piga <strong>*150*60#</strong> &rarr; Chagua 5. Lipa Bili &rarr; Weka Kumbukumbu Namba (<strong>{{ $invoice->control_number }}</strong>) &rarr; Weka Kiasi (<strong>15,000</strong>) &rarr; Weka PIN.</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="button" @click="activeTab = 'manual'" class="w-full py-3 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl text-xs font-bold transition flex items-center justify-center gap-2">
                                <span>Umeshamaliza kulipa? Bonyeza hapa kuweka namba ya muamala &rarr;</span>
                            </button>
                        </div>
                    </div>

                    <!-- TAB 3: Manual Reference Entry -->
                    <div x-show="activeTab === 'manual'" class="space-y-5" style="display: none;">
                        <div>
                            <h3 class="text-sm font-black text-slate-900 font-heading">Thibitisha Malipo kwa Namba ya Muamala</h3>
                            <p class="text-xs text-slate-500">Ikiwa umelipa tayari kwa Control Number kupitia M-Pesa, Tigo Pesa, au Benki, weka namba ya muamala uliyopokea kwenye SMS hapa chini.</p>
                        </div>

                        <form action="{{ route('register.payment.process', $invoice->id) }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="payment_method" value="mobile_money">
                            <input type="hidden" name="payment_mode" value="manual_ref">

                            <div>
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                    Namba ya Muamala (SMS Transaction Reference) <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="transaction_reference" required placeholder="Mfano: MP2026112001 au QW987XYZ" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm font-mono uppercase focus:bg-white focus:border-emerald-500 outline-none">
                            </div>

                            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl text-xs transition shadow-md flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                <span>Thibitisha Namba ya Muamala &rarr;</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <!-- Live Push STK Interactive Modal Simulation -->
    <div x-show="isProcessing" 
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm"
         style="display: none;">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 text-center space-y-5">
            
            <!-- Step 1: Sending request -->
            <div x-show="processingStep === 1" class="space-y-4">
                <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto animate-pulse">
                    <svg class="w-8 h-8 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                </div>
                <h3 class="text-base font-black text-slate-900 font-heading">Inatuma Ombi la Malipo...</h3>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Tafadhali subiri, ombi la malipo ya <strong>TZS 15,000</strong> linatumwa kwa mtandao wa <span class="font-bold text-emerald-700 uppercase" x-text="selectedProvider"></span> kwenye namba <span class="font-mono font-bold" x-text="phoneNumber"></span>.
                </p>
            </div>

            <!-- Step 2: Prompt waiting for PIN -->
            <div x-show="processingStep === 2" class="space-y-4">
                <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto shadow-xs">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-black text-slate-900 font-heading">Tafadhali Ingiza PIN Kwenye Simu Yako</h3>
                    <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                        Ujumbe umetumwa kwenye simu yako: <strong>Lipa TZS 15,000 kwenda TEVDA</strong>. Weka PIN yako ya simu kukamilisha muamala.
                    </p>
                </div>

                <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 text-xs text-emerald-900 font-semibold flex items-center justify-between">
                    <span>Kiasi:</span>
                    <span class="font-bold font-mono text-sm text-emerald-800">TZS 15,000</span>
                </div>

                <!-- Complete Button -->
                <form action="{{ route('register.payment.process', $invoice->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" value="mobile_money">
                    <input type="hidden" name="provider" :value="selectedProvider">
                    <input type="hidden" name="phone" :value="phoneNumber">
                    <input type="hidden" name="payment_mode" value="push_stk">

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black py-3.5 px-6 rounded-xl text-xs transition shadow-md flex items-center justify-center gap-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        <span>Nimeshaingiza PIN • Thibitisha Malipo &rarr;</span>
                    </button>
                </form>

                <button type="button" @click="isProcessing = false" class="text-xs text-slate-400 hover:text-slate-600 transition">
                    Ghairi / Chagua njia nyingine
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
