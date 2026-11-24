@extends('layouts.portal')

@section('title', 'My Profile — TEVDA Member Portal')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
            <div>
                <h1 class="text-xl font-bold text-[#002040] font-heading mb-0.5">Member Profile & Contact Details</h1>
                <p class="text-xs text-slate-500">Manage your contact information, next of kin, emergency contacts, and electric vehicle details.</p>
            </div>
            @if($member->status === 'approved')
                <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#108010]/10 text-[#108010] border border-[#108010]/20 flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    <span>Active Member</span>
                </span>
            @endif
        </div>

        @include('partials.alerts')

        <form action="{{ route('portal.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Basic & Contact Info -->
            <div class="space-y-4">
                <h2 class="text-xs font-bold text-[#002040] uppercase tracking-wider">Account & Contact Information</h2>
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
                        <input type="text" name="phone" required value="{{ old('phone', $member->phone) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                        @error('phone')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                        <input type="email" disabled value="{{ $member->email }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Operating Region (Mkoa)</label>
                        <input type="text" disabled value="{{ $member->region?->name ?? 'Tanzania' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">District (Wilaya)</label>
                        <input type="text" disabled value="{{ $member->district?->name ?? '-' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Ward (Kata)</label>
                        <input type="text" name="ward_name" value="{{ old('ward_name', $member->ward_name ?? $member->ward?->name) }}" placeholder="e.g. Sinza, Mwenge, Kariakoo" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Physical Address / Operating Hub <span class="text-rose-500">*</span></label>
                        <input type="text" name="physical_address" required value="{{ old('physical_address', $member->physical_address) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                        @error('physical_address')<span class="text-[11px] text-rose-500">{{ $message }}</span>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Occupation / Role</label>
                        <input type="text" name="occupation" value="{{ old('occupation', $member->occupation) }}" placeholder="e.g. Commercial EV Driver" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">EV Sub-Sector</label>
                        <input type="text" name="ev_sector" value="{{ old('ev_sector', $member->ev_sector) }}" placeholder="e.g. Passenger, Delivery, Maintenance" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-700 mb-1">Employer / Saccos / Fleet Group</label>
                        <input type="text" name="employer" value="{{ old('employer', $member->employer) }}" placeholder="e.g. Self-employed / Twiga Boda Boda Saccos" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 2: Next of Kin -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xs font-bold text-[#002040] uppercase tracking-wider">Next of Kin / Emergency Contact</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Contact Name</label>
                        <input type="text" name="next_of_kin_name" value="{{ old('next_of_kin_name', $member->next_of_kin_name) }}" placeholder="e.g. Asha Bakari" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Relationship</label>
                        <input type="text" name="next_of_kin_relationship" value="{{ old('next_of_kin_relationship', $member->next_of_kin_relationship) }}" placeholder="e.g. Spouse / Brother / Parent" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number</label>
                        <input type="text" name="next_of_kin_phone" value="{{ old('next_of_kin_phone', $member->next_of_kin_phone) }}" placeholder="+255..." class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] focus:bg-white outline-none">
                    </div>
                </div>
            </div>

            <!-- Section 3: Profile Photo Update -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h2 class="text-xs font-bold text-[#002040] uppercase tracking-wider">Profile Photo & Identity</h2>
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-2xl bg-[#002040] border-2 border-[#E5B51B]/40 text-[#E5B51B] flex items-center justify-center font-black text-2xl overflow-hidden shrink-0 shadow-md">
                        @if ($member->passport_photo_path)
                            <img src="{{ \App\Helpers\ImageHelper::getUrl($member->passport_photo_path) }}" alt="{{ $member->full_name }}" class="w-full h-full object-cover">
                        @else
                            <span>{{ strtoupper(substr($member->full_name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="space-y-1">
                        <label class="block text-xs font-bold text-slate-700">Update Passport Photo</label>
                        <input type="file" name="passport_photo" class="text-xs text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#108010]/10 file:text-[#108010] hover:file:bg-[#108010]/20">
                        <p class="text-[10px] text-slate-400">Upload a clear front-facing portrait (JPG, PNG up to 2MB). This is printed on your digital and physical ID card.</p>
                    </div>
                </div>
            </div>

            <div class="pt-6 border-t border-slate-100">
                <button type="submit" class="bg-[#108010] hover:bg-[#0c660c] text-white font-bold px-8 py-3.5 rounded-xl text-xs sm:text-sm transition-all shadow-md shadow-[#108010]/20 cursor-pointer">
                    Save Profile Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
