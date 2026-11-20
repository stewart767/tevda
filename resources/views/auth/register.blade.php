@extends('layouts.app')

@section('title', 'Online Membership Registration — TEVDA')

@section('content')
<!-- Hero Header -->
<div class="relative bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-950 text-white py-10 lg:py-14 overflow-hidden border-b border-emerald-900/40">
    <div class="absolute -left-20 -top-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-900/80 border border-emerald-700/60 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest text-emerald-300 mb-3 shadow-xs">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>Hatua ya 1 ya 3 • Usajili Rasmi wa Mwanachama TEVDA</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black font-heading tracking-tight text-white mb-3">
            Jiunge na Umoja wa Madereva (TEVDA)
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
            Jaza taarifa zako za awali hapa chini. Baada ya kujaza, utalipia <strong>TZS 5,000</strong> (Ada ya kuendelea na fomu) na <strong>TZS 10,000</strong> (Ada ya Cheti cha Uanachama) ili kukamilisha usajili na kutoa Cheti chako rasmi.
        </p>

        <!-- Stepper Indicator -->
        <div class="mt-6 max-w-xl mx-auto grid grid-cols-3 gap-2 text-xs font-bold">
            <div class="p-2.5 rounded-xl bg-emerald-500/20 border border-emerald-400/50 text-emerald-300 flex items-center justify-center gap-2">
                <span class="w-5 h-5 rounded-full bg-emerald-500 text-slate-950 flex items-center justify-center text-[10px] font-black">1</span>
                <span>Taarifa za Awali</span>
            </div>
            <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-400 flex items-center justify-center gap-2">
                <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center text-[10px]">2</span>
                <span>Malipo (15,000 TZS)</span>
            </div>
            <div class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-slate-400 flex items-center justify-center gap-2">
                <span class="w-5 h-5 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center text-[10px]">3</span>
                <span>Leseni & Cheti</span>
            </div>
        </div>
    </div>
</div>

<div class="py-10 bg-slate-100 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        @include('partials.alerts')

        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-2xl relative" x-data="{
            selectedCategory: '{{ old('category_id', $selectedCategory->id ?? 1) }}',
            selectedCategorySlug: '{{ $selectedCategory->slug ?? 'full-member' }}',
            selectedRegion: '{{ old('region_id', $regions->first()?->id ?? '') }}',
            regionsData: {{ Js::from($regions) }},
            get currentDistricts() {
                const reg = this.regionsData.find(r => r.id == this.selectedRegion);
                return reg ? (reg.districts || []) : [];
            }
        }">

            <form action="{{ route('register.submit') }}" method="POST" class="space-y-8" autocomplete="off">
                @csrf

                <!-- Section 1: Membership Category Selection -->
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">1</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Chagua Kundi la Uanachama (Membership Category)</h2>
                            <p class="text-xs text-slate-500">Chagua daraja la uanachama kulingana na shughuli zako kwenye usafiri wa vyombo vya umeme (EV).</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-3">
                        @foreach ($categories as $cat)
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between" 
                                   :class="selectedCategory == '{{ $cat->id }}' ? 'border-emerald-600 bg-emerald-50/50 shadow-md ring-2 ring-emerald-500/20' : 'border-slate-200 bg-slate-50 hover:border-slate-300'">
                                <div class="flex items-start justify-between mb-2">
                                    <input type="radio" 
                                           name="category_id" 
                                           value="{{ $cat->id }}" 
                                           x-model="selectedCategory" 
                                           @change="selectedCategorySlug = '{{ $cat->slug }}'"
                                           class="text-emerald-600 focus:ring-emerald-500 mt-1">
                                    <span class="text-[10px] uppercase font-black text-emerald-800 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                                        TZS 15,000 (Usajili + Cheti)
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-black text-sm text-slate-900 font-heading">{{ $cat->name }}</h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $cat->description }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Section 2: Personal & Contact Details (Exactly as shown in screenshot) -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">2</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Personal & Contact Details</h2>
                            <p class="text-xs text-slate-500">Provide legal identification details as registered in official civil records.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3">
                        <!-- FULL LEGAL NAME -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                FULL LEGAL NAME (JINA KAMILI) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}" autocomplete="off" placeholder="Mfano: Juma Ally Rashidi (Kama ilivyo kwenye NIDA / Leseni)" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- MOBILE PHONE NUMBER -->
                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                MOBILE PHONE NUMBER (NAMBARI YA SIMU) <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="phone" name="phone" required value="{{ old('phone') }}" autocomplete="off" placeholder="Mfano: 0757 700 401 au 06..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition font-mono">
                            <p class="text-[10px] text-slate-400 mt-1">Used to track application status & receive payment SMS</p>
                            @error('phone') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- EMAIL ADDRESS -->
                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                EMAIL ADDRESS (BARUA PEPE) <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" autocomplete="new-password" placeholder="Mfano: dereva@mfano.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            @error('email') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- GENDER -->
                        <div>
                            <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                GENDER <span class="text-rose-500">*</span>
                            </label>
                            <select id="gender" name="gender" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                <option value="male" {{ old('gender', 'male') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('gender') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- DATE OF BIRTH -->
                        <div>
                            <label for="date_of_birth" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                DATE OF BIRTH <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            @error('date_of_birth') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- REGION -->
                        <div>
                            <label for="region_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                REGION (MKOA) <span class="text-rose-500">*</span>
                            </label>
                            <select id="region_id" name="region_id" x-model="selectedRegion" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                <option value="">-- Chagua Mkoa / Region --</option>
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                            @error('region_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- DISTRICT -->
                        <div>
                            <label for="district_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                DISTRICT (WILAYA) <span class="text-rose-500">*</span>
                            </label>
                            <select id="district_id" name="district_id" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                <option value="">-- Chagua Wilaya / District --</option>
                                <template x-for="dist in currentDistricts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name" :selected="dist.id == {{ old('district_id', 0) }}"></option>
                                </template>
                            </select>
                            @error('district_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- WARD (KATA) -->
                        <div>
                            <label for="ward_name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                WARD (KATA) <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="ward_name" name="ward_name" required value="{{ old('ward_name') }}" autocomplete="off" placeholder="Mfano: Sinza, Kimara, Mwenge, Kariakoo, Sekei" list="common_wards" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            <datalist id="common_wards">
                                <option value="Sinza">
                                <option value="Kijitonyama">
                                <option value="Mwenge">
                                <option value="Ubungo">
                                <option value="Kimara">
                                <option value="Kariakoo">
                                <option value="Upanga">
                                <option value="Ilala">
                                <option value="Buguruni">
                                <option value="Magomeni">
                                <option value="Mikocheni">
                                <option value="Msasani">
                                <option value="Kawe">
                                <option value="Mbezi">
                                <option value="Kigamboni">
                                <option value="Temeke">
                                <option value="Mbagala">
                                <option value="Sekei">
                                <option value="Themi">
                                <option value="Kaloleni">
                                <option value="Ngarenaro">
                                <option value="Tambukareli">
                                <option value="Kizota">
                                <option value="Makole">
                                <option value="Nyamagana">
                                <option value="Mabatini">
                            </datalist>
                            @error('ward_name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- PHYSICAL ADDRESS / STATION HUB -->
                        <div class="sm:col-span-2">
                            <label for="physical_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                PHYSICAL ADDRESS / STATION HUB <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="physical_address" name="physical_address" required value="{{ old('physical_address') }}" autocomplete="off" placeholder="Mfano: Sinza Mori, Mtaa wa Shekilango / Kituo cha Bajaj" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            @error('physical_address') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Account Password Section -->
                <div class="pt-6 border-t border-slate-200">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Nenosiri la Kuingilia kwenye Mfumo (Member Portal Password)</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Nenosiri (Password) <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required autocomplete="new-password" placeholder="Weka nenosiri lako (angalau herufi 6)" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                            @error('password') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Rudia Nenosiri (Confirm Password) <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password" placeholder="Rudia nenosiri lilelile hapa" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm placeholder:text-slate-500 placeholder:opacity-100 focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Section: Mandatory Payment Breakdown Notice -->
                <div class="p-5 sm:p-6 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 border-2 border-emerald-300/80 shadow-xs space-y-3">
                    <div class="flex items-center gap-2.5 text-emerald-900 font-extrabold text-sm sm:text-base">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Muhtasari wa Ada ya Kuendelea na Fomu ya Usajili</span>
                    </div>

                    <div class="divide-y divide-emerald-200/60 text-xs sm:text-sm">
                        <div class="py-2 flex justify-between items-center text-slate-700">
                            <span>1. Ada ya kuendelea na fomu ya usajili:</span>
                            <span class="font-bold text-slate-900 font-mono">TZS 5,000</span>
                        </div>
                        <div class="py-2 flex justify-between items-center text-slate-700">
                            <span>2. Ada ya Cheti Rasmi cha Uanachama (Certificate):</span>
                            <span class="font-bold text-slate-900 font-mono">TZS 10,000</span>
                        </div>
                        <div class="pt-2.5 flex justify-between items-center text-emerald-950 font-black text-sm sm:text-base">
                            <span>Jumla Inayotakiwa Kulipwa (Total Payable):</span>
                            <span class="font-mono text-lg text-emerald-700">TZS 15,000</span>
                        </div>
                    </div>

                    <p class="text-[11px] text-slate-600 leading-relaxed pt-1">
                        <span class="font-bold text-emerald-900">Ufafanuzi:</span> Baada ya kubonyeza kitufe hapa chini, utapelekwa kwenye mfumo salama wa malipo wa simu (M-Pesa, Tigo Pesa, Airtel Money, Halopesa au Benki). Mara tu malipo yatakapothibitishwa, fomu ya leseni na chombo itafunguka na Cheti chako rasmi kitatolewa papo hapo.
                    </p>
                </div>

                <!-- Terms Agreement -->
                <div class="flex items-start gap-3">
                    <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded mt-0.5">
                    <label for="terms" class="text-xs text-slate-600 leading-snug">
                        Nathibitisha kuwa taarifa zote nilizotoa ni za kweli na sahihi, na ninakubali <a href="{{ route('terms') }}" target="_blank" class="text-emerald-700 font-bold underline">Sheria za Umoja wa TEVDA</a> na <a href="{{ route('code_of_conduct') }}" target="_blank" class="text-emerald-700 font-bold underline">Mwongozo wa Maadili ya Madereva</a>.
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black py-4 px-6 rounded-2xl text-sm sm:text-base transition-all duration-200 shadow-xl shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                        <span>Endelea na Malipo (Lipa TZS 15,000) &rarr;</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                    <p class="text-[11px] text-slate-500 text-center mt-2.5">
                        Mfumo utatoa Control Number ya malipo mara moja kupitia M-Pesa, Tigo Pesa, Airtel Money, au Benki.
                    </p>
                </div>

            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500">
                <div>
                    Umeshajiandikisha tayari? 
                    <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Ingia Kwenye Akaunti Yako &rarr;</a>
                </div>
                <div>
                    Unataka kufuatilia maombi yako?
                    <a href="{{ route('track.application') }}" class="font-bold text-cyan-700 hover:text-cyan-800">Fuatilia Hali ya Maombi &rarr;</a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
