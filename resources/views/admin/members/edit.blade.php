@extends('layouts.admin')

@section('title', 'Edit Member: ' . $member->full_name)

@section('content')
<div class="space-y-6" x-data="{
    selectedCategory: '{{ old('category_id', $member->category_id) }}',
    selectedRegion: '{{ old('region_id', $member->region_id ?? '') }}',
    selectedDistrict: '{{ old('district_id', $member->district_id ?? '') }}',
    currentStatus: '{{ old('status', $member->status) }}',
    passportPreview: '{{ $member->passport_photo_path ? asset('storage/' . $member->passport_photo_path) : '' }}',
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
    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.members.show', $member->id) }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-2xl font-black text-slate-900 font-heading">Edit Member Profile</h1>
                    <span class="px-2 py-0.5 rounded text-[11px] font-mono font-bold bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $member->membership_number ?? 'Pending No.' }}
                    </span>
                </div>
                <p class="text-xs text-slate-500">Update personal credentials, geographic location, EV data, next-of-kin, and membership status.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('admin.members.show', $member->id) }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-eye"></i> View Profile
            </a>
        </div>
    </div>

    @include('partials.alerts')

    <form method="POST" action="{{ route('admin.members.update', $member->id) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Primary Details, Categories, Driving/EV, Next of Kin -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Account & Personal Identity -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">1</div>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Personal & Account Information</h2>
                            <p class="text-xs text-slate-500">Identity and primary contact credentials</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name', $member->full_name) }}" placeholder="e.g. Juma Rashidi Athumani" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('name')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email', $member->email) }}" placeholder="member@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('email')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" required value="{{ old('phone', $member->phone) }}" placeholder="+255 7XX XXX XXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('phone')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('date_of_birth')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender <span class="text-rose-500">*</span></label>
                            <select name="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="male" {{ old('gender', $member->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $member->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $member->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">National ID (NIDA Number)</label>
                            <input type="text" name="nida_number" value="{{ old('nida_number', $member->nida_number) }}" placeholder="20-digit NIDA number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Reset Portal Password</label>
                            <input type="password" name="password" placeholder="Leave blank to keep current password" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <span class="text-[10px] text-slate-400">Only fill this if you wish to reset member's portal password.</span>
                        </div>
                    </div>
                </div>

                <!-- 2. Membership Category -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">2</div>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Membership Category & Tier</h2>
                            <p class="text-xs text-slate-500">Designation and membership category</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach($categories as $cat)
                            <label class="p-4 rounded-xl border-2 cursor-pointer transition flex flex-col justify-between"
                                   :class="selectedCategory == {{ $cat->id }} ? 'border-emerald-600 bg-emerald-50/50 shadow-xs' : 'border-slate-200 bg-slate-50 hover:border-slate-300'">
                                <div class="flex items-start justify-between mb-2">
                                    <input type="radio" name="category_id" value="{{ $cat->id }}" x-model="selectedCategory" class="text-emerald-600 focus:ring-emerald-500 mt-0.5">
                                    <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">
                                        @if($cat->registration_fee > 0)
                                            {{ number_format($cat->registration_fee) }} TZS
                                        @else
                                            TZS 50,000 (Std)
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">{{ $cat->name }}</h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 mt-0.5">{{ $cat->description }}</p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="pt-2 flex items-center gap-2">
                        <input type="checkbox" id="is_founding_member" name="is_founding_member" value="1" {{ old('is_founding_member', $member->is_founding_member) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                        <label for="is_founding_member" class="text-xs font-semibold text-slate-700 cursor-pointer">
                            Mark as <strong class="text-amber-700">Founding Member</strong> (Special recognition badge)
                        </label>
                    </div>
                </div>

                <!-- 3. Geographic & Operational Location -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">3</div>
                        <h2 class="text-base font-black text-slate-900">Geographic & Station Location</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Region <span class="text-rose-500">*</span></label>
                            <select name="region_id" x-model="selectedRegion" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                @foreach($regions as $reg)
                                    <option value="{{ $reg->id }}">{{ $reg->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">District</label>
                            <select name="district_id" x-model="selectedDistrict" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="">Select District</option>
                                <template x-for="dist in currentDistricts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name" :selected="dist.id == selectedDistrict"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Physical Address / Hub <span class="text-rose-500">*</span></label>
                            <input type="text" name="physical_address" required value="{{ old('physical_address', $member->physical_address) }}" placeholder="e.g. Sinza Kijiweni / Kariakoo Hub" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>
                    </div>
                </div>

                <!-- 4. Driving Licence & Electric Vehicle Details -->
                @php
                    $primaryVehicle = $member->primaryVehicle;
                @endphp
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">4</div>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Driving Licence & Electric Vehicle (EV)</h2>
                            <p class="text-xs text-slate-500">Driver certifications and assigned clean mobility asset</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Driving Licence Number</label>
                            <input type="text" name="driving_licence_number" value="{{ old('driving_licence_number', $member->driving_licence_number) }}" placeholder="e.g. DL-1082944" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Licence Classes</label>
                            <input type="text" name="licence_class" value="{{ old('licence_class', $member->licence_class) }}" placeholder="e.g. A, B, C3, D" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Licence Expiry Date</label>
                            <input type="date" name="licence_expiry_date" value="{{ old('licence_expiry_date', $member->licence_expiry_date ? $member->licence_expiry_date->format('Y-m-d') : '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Type</label>
                            <select name="vehicle_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="three_wheeler" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'three_wheeler' ? 'selected' : '' }}>Electric Three-Wheeler (Bajaji)</option>
                                <option value="two_wheeler" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'two_wheeler' ? 'selected' : '' }}>Electric Motorcycle / Boda Boda</option>
                                <option value="passenger_car" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'passenger_car' ? 'selected' : '' }}>Electric Passenger Taxi / Car</option>
                                <option value="van" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'van' ? 'selected' : '' }}>Electric Cargo Van</option>
                                <option value="bus" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'bus' ? 'selected' : '' }}>Electric Minibus / Bus</option>
                                <option value="commercial_truck" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'commercial_truck' ? 'selected' : '' }}>Electric Commercial Truck</option>
                                <option value="other" {{ old('vehicle_type', $primaryVehicle?->vehicle_type) === 'other' ? 'selected' : '' }}>Other Clean Vehicle</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Plate / Registration</label>
                            <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration', $primaryVehicle?->registration_number) }}" placeholder="e.g. T 412 EXB" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Make / Brand</label>
                            <input type="text" name="vehicle_make" value="{{ old('vehicle_make', $primaryVehicle?->make) }}" placeholder="e.g. Ampersand, Roam, Watu" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Model</label>
                            <input type="text" name="vehicle_model" value="{{ old('vehicle_model', $primaryVehicle?->model) }}" placeholder="e.g. Model E-3, Roam Air" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Ownership Type</label>
                            <select name="ownership_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="driver_operated" {{ old('ownership_type', $primaryVehicle?->ownership_type) === 'driver_operated' ? 'selected' : '' }}>Driver Operated</option>
                                <option value="owned" {{ old('ownership_type', $primaryVehicle?->ownership_type) === 'owned' ? 'selected' : '' }}>Self Owned</option>
                                <option value="leased" {{ old('ownership_type', $primaryVehicle?->ownership_type) === 'leased' ? 'selected' : '' }}>Leased / Rent-to-Own</option>
                                <option value="company_owned" {{ old('ownership_type', $primaryVehicle?->ownership_type) === 'company_owned' ? 'selected' : '' }}>Company / Fleet Owned</option>
                                <option value="other" {{ old('ownership_type', $primaryVehicle?->ownership_type) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Charging / Power Type</label>
                            <select name="charging_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="battery_swap" {{ old('charging_type', $primaryVehicle?->charging_type) === 'battery_swap' ? 'selected' : '' }}>Battery Swapping Network</option>
                                <option value="ac_slow" {{ old('charging_type', $primaryVehicle?->charging_type) === 'ac_slow' ? 'selected' : '' }}>AC Plug-in Slow Charging</option>
                                <option value="dc_fast" {{ old('charging_type', $primaryVehicle?->charging_type) === 'dc_fast' ? 'selected' : '' }}>DC Fast Charging</option>
                                <option value="dual" {{ old('charging_type', $primaryVehicle?->charging_type) === 'dual' ? 'selected' : '' }}>Dual (Plug-in + Swap)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Battery Capacity (kWh)</label>
                            <input type="number" step="0.1" name="battery_capacity_kwh" value="{{ old('battery_capacity_kwh', $primaryVehicle?->battery_capacity_kwh) }}" placeholder="e.g. 3.2" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Daily Average Distance (KM)</label>
                            <input type="number" name="daily_average_km" value="{{ old('daily_average_km', $primaryVehicle?->daily_average_km) }}" placeholder="e.g. 120" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>
                    </div>
                </div>

                <!-- 5. Next of Kin & Professional Info -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">5</div>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Next of Kin & Professional Details</h2>
                            <p class="text-xs text-slate-500">Emergency contacts and occupational profile</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Next of Kin Full Name</label>
                            <input type="text" name="next_of_kin_name" value="{{ old('next_of_kin_name', $member->next_of_kin_name) }}" placeholder="e.g. Asha Bakari" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Relationship</label>
                            <input type="text" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $member->next_of_kin_relationship) }}" placeholder="e.g. Spouse / Brother / Parent" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Next of Kin Phone</label>
                            <input type="text" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $member->next_of_kin_phone) }}" placeholder="+255 7XX XXX XXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Occupation / Role</label>
                            <input type="text" name="occupation" value="{{ old('occupation', $member->occupation) }}" placeholder="e.g. Commercial EV Driver" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">EV Sub-Sector</label>
                            <input type="text" name="ev_sector" value="{{ old('ev_sector', $member->ev_sector) }}" placeholder="e.g. Passenger Transport, Cargo" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Employer / Saccos / Fleet</label>
                            <input type="text" name="employer" value="{{ old('employer', $member->employer) }}" placeholder="e.g. Self-employed / Twiga Fleet" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">TIN / Business Reg No</label>
                            <input type="text" name="tin_number" value="{{ old('tin_number', $member->tin_number) }}" placeholder="e.g. 100-234-567" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">EV Driving Experience</label>
                            <input type="text" name="ev_experience_years" value="{{ old('ev_experience_years', $member->ev_experience_years) }}" placeholder="e.g. 2 Years" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Passport Photo, Status & Review, Document Attachments -->
            <div class="space-y-6">
                
                <!-- Passport Photo Upload Box -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm text-center space-y-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Member Passport Photo</h3>
                    <p class="text-[11px] text-slate-500">Displayed on Member Digital ID Card and Certificate</p>

                    <div class="relative w-32 h-40 mx-auto rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 overflow-hidden flex flex-col items-center justify-center group hover:border-emerald-500 transition">
                        <template x-if="passportPreview">
                            <img :src="passportPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!passportPreview">
                            <div class="text-center p-3">
                                <i class="fa-solid fa-camera text-2xl text-slate-400 group-hover:text-emerald-600 mb-1 transition"></i>
                                <span class="block text-[10px] text-slate-400">Click to Upload New Photo</span>
                            </div>
                        </template>
                        <input type="file" name="passport_photo" accept="image/*" @change="previewPhoto($event)" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>

                    <p class="text-[10px] text-slate-400">Click box to replace photo (JPG, PNG - Max 4MB)</p>
                </div>

                <!-- Membership Status & Review Workflow -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider flex items-center justify-between">
                        <span>Membership Status & Controls</span>
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Official Member Number</label>
                        <input type="text" name="membership_number" value="{{ old('membership_number', $member->membership_number) }}" placeholder="e.g. TEVDA-2026-00001 (Auto if left blank on approval)" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono font-bold focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        <span class="text-[10px] text-slate-400">Auto-generated on approval if left empty.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Current Membership Status <span class="text-rose-500">*</span></label>
                        <select name="status" x-model="currentStatus" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-bold focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <option value="approved" class="text-emerald-700 font-bold">Approved (Active Member)</option>
                            <option value="submitted" class="text-blue-700 font-bold">Submitted (Pending Review)</option>
                            <option value="payment_pending" class="text-purple-700 font-bold">Payment Pending</option>
                            <option value="payment_confirmed" class="text-teal-700 font-bold">Payment Confirmed</option>
                            <option value="under_review" class="text-amber-700 font-bold">Under Review</option>
                            <option value="documents_incomplete" class="text-orange-700 font-bold">Documents Incomplete</option>
                            <option value="additional_info_required" class="text-amber-700 font-bold">Additional Info Required</option>
                            <option value="rejected" class="text-rose-700 font-bold">Rejected</option>
                            <option value="suspended" class="text-slate-700 font-bold">Suspended</option>
                            <option value="cancelled" class="text-slate-700 font-bold">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Validity Expiry Date</label>
                        <input type="date" name="expiry_date" value="{{ old('expiry_date', $member->expiry_date ? $member->expiry_date->format('Y-m-d') : '') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Reviewer / Admin Notes</label>
                        <textarea name="reviewer_notes" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none" placeholder="Internal notes regarding this member's application or changes...">{{ old('reviewer_notes', $member->reviewer_notes) }}</textarea>
                    </div>

                    <div x-show="currentStatus === 'rejected'" x-cloak>
                        <label class="block text-xs font-bold text-rose-700 uppercase tracking-wider mb-1">Rejection Reason</label>
                        <textarea name="rejection_reason" rows="2" class="w-full bg-rose-50 border border-rose-200 rounded-xl p-3 text-xs text-rose-900 focus:ring-2 focus:ring-rose-500 focus:bg-white outline-none" placeholder="Reason communicated to member for rejection...">{{ old('rejection_reason', $member->rejection_reason) }}</textarea>
                    </div>
                </div>

                <!-- Document Uploads (Optional updates) -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Upload New / Replacement Documents</h3>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">NIDA ID Document (PDF/Image)</label>
                        <input type="file" name="nida_document" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Driving Licence Scan (PDF/Image)</label>
                        <input type="file" name="driving_licence_document" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>
                </div>

                <!-- Action Buttons Box -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-3">
                    <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider rounded-xl transition shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-floppy-disk"></i> Save & Update Information
                    </button>

                    <a href="{{ route('admin.members.show', $member->id) }}" class="w-full py-2.5 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center justify-center gap-1.5">
                        <i class="fa-solid fa-xmark"></i> Cancel & Discard
                    </a>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
