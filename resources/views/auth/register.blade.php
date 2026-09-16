@extends('layouts.app')

@section('title', 'Online Membership Registration — TEVDA')

@section('content')
<!-- Hero Header -->
<div class="relative bg-gradient-to-r from-slate-950 via-emerald-950 to-slate-950 text-white py-12 lg:py-16 overflow-hidden border-b border-emerald-900/40">
    <div class="absolute -left-20 -top-20 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -right-20 -bottom-20 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 bg-emerald-900/80 border border-emerald-700/60 px-3.5 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-widest text-emerald-300 mb-3 shadow-xs">
            <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            <span>Official Driver & Member Registration</span>
        </div>
        <h1 class="text-3xl sm:text-5xl font-black font-heading tracking-tight text-white mb-3">
            Join the TEVDA Association
        </h1>
        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto leading-relaxed">
            Fill out the official online registration form below. Upon submission, an official <strong>12-digit Payment Control Number</strong> will be issued to pay your fee and unlock your official Certificate of Membership and Smart ID Card.
        </p>
    </div>
</div>

<div class="py-12 bg-slate-100 min-h-[80vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        @include('partials.alerts')

        <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-2xl relative" x-data="{
            selectedCategory: '{{ old('category_id', $selectedCategory->id ?? 1) }}',
            selectedCategorySlug: '{{ $selectedCategory->slug ?? 'full-member' }}',
            selectedRegion: '{{ old('region_id', $regions->first()?->id ?? '') }}',
            passportPreview: null,
            previewPhoto(event) {
                const file = event.target.files[0];
                if (file) {
                    this.passportPreview = URL.createObjectURL(file);
                }
            },
            regionsData: {{ Js::from($regions) }},
            get currentDistricts() {
                const reg = this.regionsData.find(r => r.id == this.selectedRegion);
                return reg ? (reg.districts || []) : [];
            }
        }">

            <form action="{{ route('register.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Section 1: Membership Tier Selection -->
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">1</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Choose Membership Category</h2>
                            <p class="text-xs text-slate-500">Select the tier matching your role in commercial EV transport in Tanzania.</p>
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
                                        Fee: {{ number_format($cat->registration_fee > 0 ? $cat->registration_fee : 50000) }} TZS
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

                <!-- Section 2: Personal & Contact Information -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">2</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Personal & Contact Details</h2>
                            <p class="text-xs text-slate-500">Provide legal identification details as registered in official civil records.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3">
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Full Legal Name <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="As per NIDA / Driving Licence" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label for="phone" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Mobile Phone Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="phone" name="phone" required value="{{ old('phone') }}" placeholder="0757 700 401 or +255..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition font-mono">
                            <p class="text-[10px] text-slate-400 mt-1">Used to track application status & receive payment SMS</p>
                        </div>

                        <div>
                            <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Email Address <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" required value="{{ old('email') }}" placeholder="driver@example.com" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label for="gender" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Gender <span class="text-rose-500">*</span>
                            </label>
                            <select id="gender" name="gender" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="date_of_birth" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Date of Birth <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" id="date_of_birth" name="date_of_birth" required value="{{ old('date_of_birth') }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label for="region_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Operating Region <span class="text-rose-500">*</span>
                            </label>
                            <select id="region_id" name="region_id" x-model="selectedRegion" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="district_id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                District
                            </label>
                            <select id="district_id" name="district_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                <option value="">Select District</option>
                                <template x-for="dist in currentDistricts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name" :selected="dist.id == {{ old('district_id', 0) }}"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label for="physical_address" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Physical Address / Station Hub <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="physical_address" name="physical_address" required value="{{ old('physical_address') }}" placeholder="e.g. Sinza Mori, Kinondoni" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Driving Licence & Identification -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">3</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Licensing & National Identification</h2>
                            <p class="text-xs text-slate-500">Your driving licence number is used to track your application and appears on your official certificate.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3">
                        <div>
                            <label for="driving_licence_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Driving Licence Number <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="driving_licence_number" name="driving_licence_number" value="{{ old('driving_licence_number') }}" placeholder="e.g. DL-123456" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition font-mono">
                            <p class="text-[10px] text-emerald-700 font-semibold mt-1">Can be used directly to track application status</p>
                        </div>

                        <div>
                            <label for="licence_class" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Licence Class
                            </label>
                            <input type="text" id="licence_class" name="licence_class" value="{{ old('licence_class', 'C3') }}" placeholder="e.g. A, B, C3, D" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition font-mono">
                        </div>

                        <div>
                            <label for="nida_number" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                National ID (NIDA) Number
                            </label>
                            <input type="text" id="nida_number" name="nida_number" value="{{ old('nida_number') }}" placeholder="20-digit NIDA number" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition font-mono">
                        </div>
                    </div>
                </div>

                <!-- Section 4: Vehicle / Operating Fleet -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">4</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Commercial EV Vehicle Information</h2>
                            <p class="text-xs text-slate-500">Provide details of the electric vehicle or commercial fleet you operate.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3">
                        <div>
                            <label for="vehicle_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Vehicle Type
                            </label>
                            <select id="vehicle_type" name="vehicle_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                                <option value="three_wheeler" selected>Electric Three-Wheeler (Bajaj)</option>
                                <option value="two_wheeler">Electric Motorcycle / Boda Boda</option>
                                <option value="passenger_car">Electric Passenger Taxi / Car</option>
                                <option value="van">Electric Cargo Van</option>
                                <option value="bus">Electric Minibus / Bus</option>
                                <option value="commercial_truck">Electric Commercial Truck</option>
                                <option value="other">Other Clean Vehicle / None</option>
                            </select>
                        </div>

                        <div>
                            <label for="vehicle_make" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Vehicle Make & Model
                            </label>
                            <input type="text" id="vehicle_make" name="vehicle_make" value="{{ old('vehicle_make') }}" placeholder="e.g. Taifa, Wuling, TVS" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label for="vehicle_registration" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Registration Plate Number
                            </label>
                            <input type="text" id="vehicle_registration" name="vehicle_registration" value="{{ old('vehicle_registration') }}" placeholder="e.g. T 123 ABC" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition font-mono">
                        </div>
                    </div>
                </div>

                <!-- Section 5: Photo & Document Uploads -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">5</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Passport Photo & Verification Documents</h2>
                            <p class="text-xs text-slate-500">Your photo will be embedded into your official Digital ID Card and Certificate of Membership.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-3">
                        <!-- Passport Photo with Live Preview -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Passport Photo <span class="text-rose-500">*</span>
                            </label>
                            
                            <div class="w-24 h-28 mx-auto rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex items-center justify-center relative shadow-xs">
                                <template x-if="passportPreview">
                                    <img :src="passportPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!passportPreview">
                                    <div class="text-slate-400 text-xs flex flex-col items-center">
                                        <svg class="w-6 h-6 mb-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        <span>Select Photo</span>
                                    </div>
                                </template>
                            </div>

                            <input type="file" 
                                   name="passport_photo" 
                                   accept="image/*" 
                                   @change="previewPhoto($event)" 
                                   class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-emerald-600 file:text-white file:font-semibold hover:file:bg-emerald-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400">Clear face portrait photo</p>
                        </div>

                        <!-- Driving Licence Scan -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                Driving Licence Document
                            </label>
                            <input type="file" name="driving_licence_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200 file:font-medium">
                            <p class="text-[10px] text-slate-400">PDF or photo of driving licence</p>
                        </div>

                        <!-- NIDA Document -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">
                                National ID (NIDA) Document
                            </label>
                            <input type="file" name="nida_document" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200 file:font-medium">
                            <p class="text-[10px] text-slate-400">PDF or photo of NIDA card</p>
                        </div>
                    </div>
                </div>

                <!-- Section 6: Account Password & Terms -->
                <div class="pt-6 border-t border-slate-200">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="w-8 h-8 rounded-xl bg-emerald-600 text-white font-black text-xs flex items-center justify-center shadow-md">6</span>
                        <div>
                            <h2 class="text-base sm:text-lg font-black text-slate-900 font-heading">Portal Password & Submission</h2>
                            <p class="text-xs text-slate-500">Create a password to access your TEVDA member portal dashboard anytime.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-3">
                        <div>
                            <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Account Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required placeholder="At least 6 characters" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                                Confirm Password <span class="text-rose-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Repeat password" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-xs sm:text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                        </div>
                    </div>

                    <div class="flex items-start gap-3 pt-4">
                        <input id="terms" name="terms" type="checkbox" required class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded mt-0.5">
                        <label for="terms" class="text-xs text-slate-600 leading-snug">
                            I confirm that the submitted information is accurate and agree to the <a href="{{ route('terms') }}" target="_blank" class="text-emerald-700 font-bold underline">Terms of Association</a> and <a href="{{ route('code_of_conduct') }}" target="_blank" class="text-emerald-700 font-bold underline">TEVDA Driver Code of Conduct</a>.
                        </label>
                    </div>

                    <div class="pt-6">
                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black py-4 px-6 rounded-2xl text-sm sm:text-base transition-all duration-200 shadow-xl shadow-emerald-600/25 flex items-center justify-center gap-2 cursor-pointer transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Complete Registration & Generate Payment Control Number &rarr;</span>
                        </button>
                        <p class="text-[11px] text-slate-500 text-center mt-2">
                            Upon submission, you will receive your 12-digit Control Number for fee payment via M-Pesa, Tigo Pesa, or Bank.
                        </p>
                    </div>
                </div>

            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-3 text-xs text-slate-500">
                <div>
                    Already registered? 
                    <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-800">Log In to Member Portal &rarr;</a>
                </div>
                <div>
                    Looking for existing application?
                    <a href="{{ route('track.application') }}" class="font-bold text-cyan-700 hover:text-cyan-800">Track Application Status &rarr;</a>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
