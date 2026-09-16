@extends('layouts.portal')

@section('title', 'My Profile — TEVDA Member Portal')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-900 font-heading mb-0.5">Member Profile & Contact Details</h1>
                <p class="text-xs text-slate-500">Manage your contact information, next of kin, emergency contacts, and electric vehicle details.</p>
            </div>
            @if($member->status === 'approved')
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    <i class="fa-solid fa-circle-check mr-1"></i> Active Member
                </span>
            @endif
        </div>

        @include('partials.alerts')

        <form action="{{ route('portal.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Basic & Contact Info -->
            <div class="space-y-4">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Account & Contact Information</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Full Legal Name</label>
                        <input type="text" disabled value="{{ $member->full_name }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 cursor-not-allowed">
                        <span class="text-[10px] text-slate-400">To change legal name, please contact the TEVDA Secretariat.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Membership Number</label>
                        <input type="text" disabled value="{{ $member->membership_number ?? 'Pending Verification' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 font-mono cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Mobile Phone Number <span class="text-rose-500">*</span></label>
                        <input type="text" name="phone" required value="{{ old('phone', $member->phone) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        @error('phone')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" disabled value="{{ $member->email }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 cursor-not-allowed">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Physical Address / Operating Hub <span class="text-rose-500">*</span></label>
                        <input type="text" name="physical_address" required value="{{ old('physical_address', $member->physical_address) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        @error('physical_address')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Occupation / Role</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $member->occupation) }}" placeholder="e.g. Commercial EV Driver" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">EV Sub-Sector</label>
                        <input type="text" name="ev_sector" value="{{ old('ev_sector', $member->ev_sector) }}" placeholder="e.g. Passenger, Delivery, Maintenance" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Employer / Saccos / Fleet Group</label>
                        <input type="text" name="employer" value="{{ old('employer', $member->employer) }}" placeholder="e.g. Self-employed / Twiga Boda Boda Saccos" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 2: Next of Kin -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Next of Kin / Emergency Contact</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Contact Name</label>
                        <input type="text" name="next_of_kin_name" value="{{ old('next_of_kin_name', $member->next_of_kin_name) }}" placeholder="e.g. Asha Bakari" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Relationship</label>
                        <input type="text" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $member->next_of_kin_relationship) }}" placeholder="e.g. Spouse / Brother / Parent" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Emergency Phone Number</label>
                        <input type="text" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $member->next_of_kin_phone) }}" placeholder="+255 7XX XXX XXX" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 3: Electric Vehicle Details -->
            @php
                $myVehicle = $member->primaryVehicle;
            @endphp
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Electric Vehicle (EV) Details</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Vehicle Type</label>
                        <select name="vehicle_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <option value="three_wheeler" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'three_wheeler' ? 'selected' : '' }}>Electric Three-Wheeler (Bajaji)</option>
                            <option value="two_wheeler" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'two_wheeler' ? 'selected' : '' }}>Electric Motorcycle / Boda Boda</option>
                            <option value="passenger_car" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'passenger_car' ? 'selected' : '' }}>Electric Passenger Taxi / Car</option>
                            <option value="van" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'van' ? 'selected' : '' }}>Electric Cargo Van</option>
                            <option value="bus" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'bus' ? 'selected' : '' }}>Electric Minibus / Bus</option>
                            <option value="commercial_truck" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'commercial_truck' ? 'selected' : '' }}>Electric Commercial Truck</option>
                            <option value="other" {{ old('vehicle_type', $myVehicle?->vehicle_type) === 'other' ? 'selected' : '' }}>Other Clean Vehicle</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Plate Registration Number</label>
                        <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration', $myVehicle?->registration_number) }}" placeholder="e.g. T 412 EXB" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Make / Brand</label>
                        <input type="text" name="vehicle_make" value="{{ old('vehicle_make', $myVehicle?->make) }}" placeholder="e.g. Ampersand, Roam" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Model</label>
                        <input type="text" name="vehicle_model" value="{{ old('vehicle_model', $myVehicle?->model) }}" placeholder="e.g. Model E-3" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Charging Type</label>
                        <select name="charging_type" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <option value="battery_swap" {{ old('charging_type', $myVehicle?->charging_type) === 'battery_swap' ? 'selected' : '' }}>Battery Swapping Network</option>
                            <option value="ac_slow" {{ old('charging_type', $myVehicle?->charging_type) === 'ac_slow' ? 'selected' : '' }}>AC Slow Plug-in</option>
                            <option value="dc_fast" {{ old('charging_type', $myVehicle?->charging_type) === 'dc_fast' ? 'selected' : '' }}>DC Fast Charging</option>
                            <option value="dual" {{ old('charging_type', $myVehicle?->charging_type) === 'dual' ? 'selected' : '' }}>Dual Mode</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Section 4: Passport Photo -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Passport Photo</h2>
                <div class="flex flex-col sm:flex-row items-start gap-4">
                    <div class="w-20 h-24 rounded-2xl bg-slate-100 border border-slate-200 overflow-hidden shrink-0 flex items-center justify-center font-bold text-slate-400 uppercase">
                        @if($member->passport_photo_path)
                            <img src="{{ asset('storage/' . $member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($member->full_name, 0, 2) }}
                        @endif
                    </div>
                    <div class="flex-1 space-y-1">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Upload New Passport Photo</label>
                        <input type="file" name="passport_photo" accept="image/*" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                        <p class="text-[10px] text-slate-400">Format: JPG, PNG (Max 3MB). Appears on your Digital ID Card.</p>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 px-6 rounded-xl text-xs transition shadow-md shadow-emerald-600/20 flex items-center gap-2">
                    <i class="fa-solid fa-floppy-disk"></i> Update Profile Details
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
