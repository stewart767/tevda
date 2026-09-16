@extends('layouts.admin')

@section('title', 'Register New Member')

@section('content')
<div class="space-y-6" x-data="{
    selectedCategory: '{{ old('category_id', $categories->first()?->id ?? 1) }}',
    selectedRegion: '{{ old('region_id', $regions->first()?->id ?? '') }}',
    initialStatus: '{{ old('initial_status', 'approved') }}',
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
    <!-- Header & Navigation -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.members.index') }}" class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 font-heading">Register New Member</h1>
                <p class="text-xs text-slate-500">Create a member record, upload passport photo, assign category, and optionally issue digital ID & certificate.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <i class="fa-solid fa-shield-halved mr-1.5"></i> Admin Enrollment Mode
            </span>
        </div>
    </div>

    @include('partials.alerts')

    <form method="POST" action="{{ route('admin.members.store') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Primary Details & Categories -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- 1. Account & Personal Identity -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">1</div>
                        <h2 class="text-base font-black text-slate-900">Personal & Account Information</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Full Legal Name <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" required value="{{ old('name') }}" placeholder="e.g. Juma Rashidi Athumani" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('name')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Address <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" required value="{{ old('email') }}" placeholder="member@example.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('email')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Mobile Phone Number <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" required value="{{ old('phone') }}" placeholder="+255 7XX XXX XXX" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('phone')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Date of Birth</label>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            @error('date_of_birth')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Gender <span class="text-rose-500">*</span></label>
                            <select name="gender" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">National ID (NIDA Number)</label>
                            <input type="text" name="nida_number" value="{{ old('nida_number') }}" placeholder="20-digit NIDA number" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Portal Login Password</label>
                            <input type="text" name="password" value="{{ old('password') }}" placeholder="Leave blank for auto default: Tevda@2026" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                            <span class="text-[10px] text-slate-400">Default password if left blank: <strong class="font-mono">Tevda@{{ date('Y') }}</strong></span>
                        </div>
                    </div>
                </div>

                <!-- 2. Membership Category -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">2</div>
                        <div>
                            <h2 class="text-base font-black text-slate-900">Membership Category & Tier</h2>
                            <p class="text-xs text-slate-500">Select the designation for this member</p>
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
                        <input type="checkbox" id="is_founding_member" name="is_founding_member" value="1" {{ old('is_founding_member') ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
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
                            <select name="district_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="">Select District</option>
                                <template x-for="dist in currentDistricts" :key="dist.id">
                                    <option :value="dist.id" x-text="dist.name"></option>
                                </template>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Physical Address / Hub <span class="text-rose-500">*</span></label>
                            <input type="text" name="physical_address" required value="{{ old('physical_address') }}" placeholder="e.g. Sinza Kijiweni / Kariakoo Hub" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>
                    </div>
                </div>

                <!-- 4. Driving Licence & Electric Vehicle Details -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">4</div>
                        <h2 class="text-base font-black text-slate-900">Driving Licence & Electric Vehicle (EV)</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Driving Licence Number</label>
                            <input type="text" name="driving_licence_number" value="{{ old('driving_licence_number') }}" placeholder="e.g. DL-1082944" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Licence Classes</label>
                            <input type="text" name="licence_class" value="{{ old('licence_class', 'C3, D') }}" placeholder="e.g. A, B, C3, D" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Type</label>
                            <select name="vehicle_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="three_wheeler">Electric Three-Wheeler (Bajaji)</option>
                                <option value="two_wheeler">Electric Motorcycle / Boda Boda</option>
                                <option value="passenger_car">Electric Passenger Taxi / Car</option>
                                <option value="van">Electric Cargo Van</option>
                                <option value="bus">Electric Minibus / Bus</option>
                                <option value="commercial_truck">Electric Commercial Truck</option>
                                <option value="other">Other Clean Vehicle / Ecosystem</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Plate / Registration</label>
                            <input type="text" name="vehicle_registration" value="{{ old('vehicle_registration') }}" placeholder="e.g. T 412 EXB" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-mono focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Vehicle Make / Brand</label>
                            <input type="text" name="vehicle_make" value="{{ old('vehicle_make') }}" placeholder="e.g. Ampersand, Roam, Watu" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Charging / Power Type</label>
                            <select name="charging_type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                                <option value="battery_swap">Battery Swapping Network</option>
                                <option value="ac_slow">AC Plug-in Slow Charging</option>
                                <option value="dc_fast">DC Fast Charging</option>
                                <option value="dual">Dual (Plug-in + Swap)</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Passport Photo, Document Attachments & Status/Issuance -->
            <div class="space-y-6">
                
                <!-- Passport Photo Upload Box -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm text-center space-y-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Member Passport Photo</h3>
                    <p class="text-[11px] text-slate-500">Required for the Member Digital ID Card and Certificate</p>

                    <div class="relative w-32 h-40 mx-auto rounded-2xl border-2 border-dashed border-slate-300 bg-slate-50 overflow-hidden flex flex-col items-center justify-center group hover:border-emerald-500 transition">
                        <template x-if="passportPreview">
                            <img :src="passportPreview" class="w-full h-full object-cover">
                        </template>
                        <template x-if="!passportPreview">
                            <div class="text-center p-3">
                                <i class="fa-solid fa-camera text-2xl text-slate-400 group-hover:text-emerald-600 mb-1 transition"></i>
                                <span class="block text-[10px] text-slate-400">Click to Upload Passport</span>
                            </div>
                        </template>
                        <input type="file" name="passport_photo" accept="image/*" @change="previewPhoto($event)" class="absolute inset-0 opacity-0 cursor-pointer">
                    </div>

                    <p class="text-[10px] text-slate-400">Accepted formats: JPG, PNG (Max 4MB)</p>
                </div>

                <!-- Document Uploads -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Document Scans (Optional)</h3>
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">NIDA ID Document</label>
                        <input type="file" name="nida_document" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>

                    <div>
                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Driving Licence Scan</label>
                        <input type="file" name="driving_licence_document" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                    </div>
                </div>

                <!-- Initial Status & Issuance Workflow -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Initial Status & Issuance</h3>
                    
                    <div class="space-y-2">
                        <label class="p-3 rounded-xl border cursor-pointer flex items-start gap-3 transition"
                               :class="initialStatus === 'approved' ? 'border-emerald-500 bg-emerald-50/50' : 'border-slate-200 hover:bg-slate-50'">
                            <input type="radio" name="initial_status" value="approved" x-model="initialStatus" class="text-emerald-600 focus:ring-emerald-500 mt-1">
                            <div>
                                <span class="font-bold text-xs text-emerald-900 block">Approved Immediately</span>
                                <span class="text-[11px] text-emerald-700 block mt-0.5">Instantly generates <strong>Membership Number</strong>, <strong>Digital ID Card</strong> & <strong>Official Certificate</strong>.</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border cursor-pointer flex items-start gap-3 transition"
                               :class="initialStatus === 'payment_pending' ? 'border-purple-500 bg-purple-50/50' : 'border-slate-200 hover:bg-slate-50'">
                            <input type="radio" name="initial_status" value="payment_pending" x-model="initialStatus" class="text-purple-600 focus:ring-purple-500 mt-1">
                            <div>
                                <span class="font-bold text-xs text-purple-900 block">Payment Pending (50,000 TZS)</span>
                                <span class="text-[11px] text-purple-700 block mt-0.5">Issues an invoice and requires payment verification before certificate issuance.</span>
                            </div>
                        </label>

                        <label class="p-3 rounded-xl border cursor-pointer flex items-start gap-3 transition"
                               :class="initialStatus === 'submitted' ? 'border-blue-500 bg-blue-50/50' : 'border-slate-200 hover:bg-slate-50'">
                            <input type="radio" name="initial_status" value="submitted" x-model="initialStatus" class="text-blue-600 focus:ring-blue-500 mt-1">
                            <div>
                                <span class="font-bold text-xs text-blue-900 block">Submitted (Pending Review)</span>
                                <span class="text-[11px] text-blue-700 block mt-0.5">Queues member application in the Secretariat review list.</span>
                            </div>
                        </label>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <button type="submit" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider rounded-xl transition shadow-md shadow-emerald-600/20 flex items-center justify-center gap-2">
                            <i class="fa-solid fa-user-plus"></i> Save & Register Member
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </form>
</div>
@endsection
