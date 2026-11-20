@extends('layouts.app')

@section('title', 'Kamilisha Usajili na Pata Cheti — TEVDA')

@section('content')
<!-- Hero Header -->
<div class="relative bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-950 text-white py-10 lg:py-12 overflow-hidden border-b border-emerald-900/40">
    <div class="absolute -left-20 -top-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-900/80 border border-emerald-700/60 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest text-emerald-300 mb-3 shadow-sm">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Hatua ya 3 ya 3 • Hatua ya Mwisho ya Fomu</span>
        </div>
        <h1 class="text-3xl sm:text-4xl font-black font-heading tracking-tight text-white mb-2">
            Kamilisha Taarifa za Leseni, Chombo na Picha
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
            Malipo yako ya <strong>TZS 15,000</strong> yamekamilika. Weka taarifa za leseni ya udereva, chombo unachoendesha, na picha ya pasipoti ili kutoa rasmi <strong>Cheti chako cha Uanachama</strong> na Kitambulisho.
        </p>

        <!-- Stepper Indicator -->
        <div class="mt-6 max-w-xl mx-auto grid grid-cols-3 gap-2 text-xs font-bold">
            <div class="p-2.5 rounded-xl bg-emerald-500/20 border border-emerald-400/50 text-emerald-300 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                <span>1. Taarifa za Awali</span>
            </div>
            <div class="p-2.5 rounded-xl bg-emerald-500/20 border border-emerald-400/50 text-emerald-300 flex items-center justify-center gap-2">
                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                <span>2. Malipo Imelipwa</span>
            </div>
            <div class="p-2.5 rounded-xl bg-emerald-500 text-slate-950 font-black flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/30">
                <span class="w-5 h-5 rounded-full bg-slate-950 text-emerald-400 flex items-center justify-center text-[10px] font-black">3</span>
                <span>3. Leseni & Cheti</span>
            </div>
        </div>
    </div>
</div>

<div class="py-10 bg-slate-100 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

        @include('partials.alerts')

        <!-- Congratulatory Payment Verified Banner -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white shadow-lg flex items-center justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center shrink-0">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-base font-heading">Malipo ya TZS 15,000 Yamethibitishwa Kikamilifu!</h3>
                    <p class="text-xs text-emerald-100 mt-0.5">Ada ya usajili (TZS 5,000) na Ada ya Cheti (TZS 10,000) zimethibitishwa. Jaza fomu ya mwisho hapa chini ili kupokea Cheti chako mara moja.</p>
                </div>
            </div>
            <span class="hidden sm:inline-block px-3 py-1 bg-white/20 rounded-full text-xs font-mono font-bold">PAID ✓</span>
        </div>

        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-2xl relative" x-data="{
            passportPreview: '{{ $member->photo_url ?? '' }}',
            previewPhoto(event) {
                const file = event.target.files[0];
                if (file) {
                    this.passportPreview = URL.createObjectURL(file);
                }
            }
        }">

            <form action="{{ route('register.complete_profile.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Section 3: Licensing & Identification -->
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">1</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Taarifa za Leseni ya Udereva & NIDA</h2>
                            <p class="text-xs text-slate-500">Namba ya leseni itaingizwa kwenye Cheti chako rasmi cha Uanachama.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3">
                        <div>
                            <label for="driving_licence_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Namba ya Leseni ya Udereva <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="driving_licence_number" name="driving_licence_number" required value="{{ old('driving_licence_number', $member->driving_licence_number) }}" placeholder="e.g. DL-123456" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm @error('driving_licence_number') border-rose-400 bg-rose-50/30 @enderror">
                            @error('driving_licence_number') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="licence_class" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Daraja la Leseni (Class)
                            </label>
                            <input type="text" id="licence_class" name="licence_class" value="{{ old('licence_class', $member->licence_class ?? 'C3') }}" placeholder="e.g. A, B, C3, D" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm">
                        </div>

                        <div>
                            <label for="nida_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Namba ya NIDA (Hiari)
                            </label>
                            <input type="text" id="nida_number" name="nida_number" value="{{ old('nida_number', $member->nida_number) }}" placeholder="Namba ya NIDA ya tarakimu 20" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Vehicle / Operating Fleet -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">2</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Chombo cha Umeme (EV) Unachoendesha</h2>
                            <p class="text-xs text-slate-500">Toa maelezo ya chombo unachoendesha kibiashara.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3">
                        <div>
                            <label for="vehicle_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Aina ya Chombo <span class="text-rose-500">*</span>
                            </label>
                            <select id="vehicle_type" name="vehicle_type" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm">
                                <option value="three_wheeler" selected>Bajaji ya Umeme (Three-Wheeler)</option>
                                <option value="two_wheeler">Pikipiki / Boda Boda ya Umeme</option>
                                <option value="passenger_car">Gari ya Abiria / Taxi ya Umeme</option>
                                <option value="van">Gari ya Mizigo / Cargo Van ya Umeme</option>
                                <option value="bus">Daladala / Basi la Umeme</option>
                                <option value="commercial_truck">Lori la Umeme (Commercial Truck)</option>
                                <option value="other">Kingine / Sina Chombo Kwa Sasa</option>
                            </select>
                        </div>

                        <div>
                            <label for="vehicle_make" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Kampuni & Model ya Chombo
                            </label>
                            <input type="text" id="vehicle_make" name="vehicle_make" value="{{ old('vehicle_make') }}" placeholder="e.g. Taifa, Wuling, TVS, BYD" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm">
                        </div>

                        <div>
                            <label for="vehicle_registration" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Namba ya Usajili wa Chombo (Plates)
                            </label>
                            <input type="text" id="vehicle_registration" name="vehicle_registration" value="{{ old('vehicle_registration') }}" placeholder="e.g. T 123 ABC" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm font-mono focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition shadow-sm">
                        </div>
                    </div>
                </div>

                <!-- Section 5: Passport Photo & Documents -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">3</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Picha ya Pasipoti & Viambatisho</h2>
                            <p class="text-xs text-slate-500">Picha ya pasipoti itawekwa kwenye Cheti chako cha Uanachama na Kitambulisho cha Kidijitali.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3">
                        <!-- Passport Photo -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Picha ya Pasipoti (Passport) <span class="text-rose-500">*</span>
                            </label>
                            
                            <div class="w-24 h-28 mx-auto rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex items-center justify-center relative shadow-sm">
                                <template x-if="passportPreview">
                                    <img :src="passportPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!passportPreview">
                                    <div class="text-slate-400 text-xs flex flex-col items-center">
                                        <svg class="w-6 h-6 mb-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Chagua Picha</span>
                                    </div>
                                </template>
                            </div>

                            <input type="file" 
                                   name="passport_photo" 
                                   accept="image/*" 
                                   {{ $member->passport_photo_path ? '' : 'required' }}
                                   @change="previewPhoto($event)" 
                                   class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-emerald-600 file:text-white file:font-semibold hover:file:bg-emerald-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400">Picha safi ya uso yenye mwanga mzuri</p>
                            @error('passport_photo') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Driving Licence Scan -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Nakala ya Leseni (Hiari)
                            </label>
                            <input type="file" name="driving_licence_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200 file:font-medium">
                            <p class="text-[10px] text-slate-400">PDF au picha ya leseni</p>
                        </div>

                        <!-- NIDA Scan -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Nakala ya NIDA (Hiari)
                            </label>
                            <input type="file" name="nida_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200 file:font-medium">
                            <p class="text-[10px] text-slate-400">PDF au picha ya kitambulisho cha NIDA</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black py-4 px-6 rounded-2xl text-sm sm:text-base transition-all duration-200 shadow-xl shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Kamilisha Usajili & Toa Cheti cha Uanachama Papo Hapo &rarr;</span>
                    </button>
                    <p class="text-[11px] text-slate-500 text-center mt-2.5">
                        Ukibonyeza hapa, Cheti chako rasmi cha Uanachama na Kitambulisho cha Kidijitali vitazalishwa na kupakuliwa mara moja.
                    </p>
                </div>

            </form>

        </div>

    </div>
</div>
@endsection
