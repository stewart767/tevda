@extends('layouts.app')

@section('title', 'Complete Membership Application — TEVDA')

@section('content')
<div class="bg-slate-900 text-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3 py-1 rounded-md border border-emerald-800">Online Membership Application</span>
        <h1 class="text-3xl sm:text-4xl font-black font-heading mt-2 mb-1">Join the TEVDA Registry</h1>
        <p class="text-slate-300 text-xs sm:text-sm">Complete your member profile and submit verified documentation to receive your official Digital ID Card and Certificate.</p>
    </div>
</div>

<div class="py-12 bg-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @include('partials.alerts')

        <div class="bg-white p-8 sm:p-10 rounded-3xl border border-slate-200 shadow-xl" x-data="{
            selectedCategory: '{{ $selectedCategory->slug ?? 'full-member' }}',
            selectedRegion: '{{ old('region_id', $member?->region_id ?? $regions->first()?->id ?? '') }}',
            passportPreview: '{{ $member?->passport_photo_path ? asset('storage/' . $member->passport_photo_path) : '' }}',
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
            
            <form action="{{ route('membership.apply.submit') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Section 1: Membership Category Selection -->
                <div>
                    <h2 class="text-lg font-bold text-slate-900 font-heading mb-1 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                        <span>Select Membership Category</span>
                    </h2>
                    <p class="text-xs text-slate-500 mb-4 ml-9">Choose the tier matching your role in commercial EV transport.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-0 sm:ml-9">
                        @foreach ($categories as $cat)
                            <label class="p-4 rounded-2xl border-2 cursor-pointer transition flex flex-col justify-between" :class="selectedCategory === '{{ $cat->slug }}' ? 'border-emerald-600 bg-emerald-50/40 shadow-xs' : 'border-slate-200 bg-slate-50 hover:border-slate-300'">
                                <div class="flex items-start justify-between mb-2">
                                    <input type="radio" name="category_id" value="{{ $cat->id }}" x-model="selectedCategory" class="text-emerald-600 focus:ring-emerald-500 mt-1">
                                    <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        @if($cat->registration_fee > 0)
                                            Fee: {{ number_format($cat->registration_fee) }} TZS
                                        @else
                                            Fee: 50,000 TZS
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-sm text-slate-900 font-heading">{{ $cat->name }}</h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $cat->description }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Section 2: Personal & Geographic Details -->
                <div class="pt-6 border-t border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 font-heading mb-1 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                        <span>Personal & Location Details</span>
                    </h2>
                    <p class="text-xs text-slate-500 mb-4 ml-9">Accurate details as registered in official civil records.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-0 sm:ml-9">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Full Legal Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="full_name" required value="{{ old('full_name', auth()->user()->name) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Date of Birth <span class="text-rose-500">*</span></label>
                            <input type="date" name="date_of_birth" required value="{{ old('date_of_birth', $member?->date_of_birth?->format('Y-m-d')) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Gender <span class="text-rose-500">*</span></label>
                            <select name="gender" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                                <option value="male" {{ old('gender', $member?->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $member?->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $member?->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" required value="{{ old('phone', auth()->user()->phone) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Operating Region <span class="text-rose-500">*</span></label>
                            <select name="region_id" x-model="selectedRegion" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                                @foreach ($regions as $region)
                                    <option value="{{ $region->id }}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">District</label>
                            <select name="district_id" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                                <option value="">Select District</option>
                                <template x-for="dist in currentDistricts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name" :selected="dist.id == {{ old('district_id', $member?->district_id ?? 0) }}"></option>
                                </template>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Physical Address / Operating Hub <span class="text-rose-500">*</span></label>
                            <input type="text" name="physical_address" required value="{{ old('physical_address', $member?->physical_address) }}" placeholder="e.g. Sinza, Kinondoni" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                        </div>
                    </div>
                </div>

                <!-- Section 3: Professional & Identification -->
                <div class="pt-6 border-t border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 font-heading mb-1 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">3</span>
                        <span>Identification & Licensing</span>
                    </h2>
                    <p class="text-xs text-slate-500 mb-4 ml-9">Full Members must provide valid driving licence information.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 ml-0 sm:ml-9">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">National ID (NIDA) Number</label>
                            <input type="text" name="nida_number" value="{{ old('nida_number', $member?->nida_number) }}" placeholder="20-digit NIDA number" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 font-mono">
                        </div>

                        <!-- Full Member Specific -->
                        <div x-show="selectedCategory === 'full-member'">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Driving Licence Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="driving_licence_number" value="{{ old('driving_licence_number', $member?->driving_licence_number) }}" placeholder="e.g. DL-XXXXXX" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 font-mono">
                        </div>

                        <div x-show="selectedCategory === 'full-member'">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Licence Class <span class="text-rose-500">*</span></label>
                            <input type="text" name="licence_class" value="{{ old('licence_class', $member?->licence_class ?? 'C3') }}" placeholder="e.g. A, B, C3, D" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Vehicle Type / Fleet</label>
                            <select name="vehicle_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                                <option value="three_wheeler" selected>Electric Three-Wheeler (Bajaj)</option>
                                <option value="two_wheeler">Electric Motorcycle / Boda Boda</option>
                                <option value="passenger_car">Electric Passenger Taxi / Car</option>
                                <option value="van">Electric Cargo Van</option>
                                <option value="bus">Electric Minibus / Bus</option>
                                <option value="commercial_truck">Electric Commercial Truck</option>
                                <option value="other">Other Clean Vehicle / None (Associate)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Document Uploads & Passport Photo -->
                <div class="pt-6 border-t border-slate-200">
                    <h2 class="text-lg font-bold text-slate-900 font-heading mb-1 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-full bg-emerald-600 text-white text-xs flex items-center justify-center font-bold">4</span>
                        <span>Passport Photo & Document Uploads</span>
                    </h2>
                    <p class="text-xs text-slate-500 mb-4 ml-9">Your passport photo is printed on your official Digital ID Card and Certificate of Membership.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 ml-0 sm:ml-9">
                        <!-- Passport Photo with Preview -->
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-center space-y-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Passport Size Photo <span class="text-rose-500">*</span></label>
                            
                            <div class="w-24 h-28 mx-auto rounded-xl border-2 border-dashed border-slate-300 bg-white overflow-hidden flex items-center justify-center relative">
                                <template x-if="passportPreview">
                                    <img :src="passportPreview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!passportPreview">
                                    <div class="text-slate-400 text-xs">
                                        <i class="fa-solid fa-camera text-xl block mb-1"></i> Photo
                                    </div>
                                </template>
                            </div>

                            <input type="file" name="passport_photo" accept="image/*" @change="previewPhoto($event)" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-emerald-600 file:text-white file:font-semibold hover:file:bg-emerald-700 cursor-pointer">
                            <p class="text-[10px] text-slate-400">Used on your Digital ID Card</p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2">
                            <label class="block text-xs font-bold text-slate-700 mb-1">NIDA Document Scan</label>
                            <input type="file" name="nida_document" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">PDF or image of NIDA card</p>
                        </div>

                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2" x-show="selectedCategory === 'full-member'">
                            <label class="block text-xs font-bold text-slate-700 mb-1">Driving Licence Scan <span class="text-rose-500">*</span></label>
                            <input type="file" name="driving_licence_document" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200">
                            <p class="text-[10px] text-slate-400 mt-1">Clear copy (front & back)</p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-slate-200">
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-8 rounded-2xl text-base transition shadow-lg flex items-center justify-center gap-2">
                        <i class="fa-solid fa-paper-plane"></i> Submit Membership Application
                    </button>
                    <p class="text-xs text-slate-500 text-center mt-3">
                        By submitting, you declare that the provided information is true and verifiable under the TEVDA Constitution.
                    </p>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
