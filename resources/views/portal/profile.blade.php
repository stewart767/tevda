@extends('layouts.portal')

@section('title', 'My Profile — TEVDA Member Portal')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm">
        <h1 class="text-xl font-bold text-slate-900 font-heading mb-1">Member Profile & Contact Details</h1>
        <p class="text-xs text-slate-500 mb-6">Manage your contact number, address, and profile photo.</p>

        <form action="{{ route('portal.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name</label>
                    <input type="text" disabled value="{{ $member->full_name }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-600 cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Membership Number</label>
                    <input type="text" disabled value="{{ $member->membership_number ?? 'Pending Approval' }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-600 font-mono cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Phone Number <span class="text-rose-500">*</span></label>
                    <input type="text" name="phone" required value="{{ old('phone', $member->phone) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Email Address</label>
                    <input type="email" disabled value="{{ $member->email }}" class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-600 cursor-not-allowed">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Physical Address / Operating Hub <span class="text-rose-500">*</span></label>
                    <input type="text" name="physical_address" required value="{{ old('physical_address', $member->physical_address) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Occupation / Role</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $member->occupation) }}" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">EV Sub-Sector</label>
                    <input type="text" name="ev_sector" value="{{ old('ev_sector', $member->ev_sector) }}" placeholder="e.g. Passenger, Delivery, Maintenance" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Update Passport Size Photo</label>
                    <input type="file" name="passport_photo" class="text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200">
                </div>
            </div>

            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-6 rounded-xl text-sm transition shadow-md">
                Update Profile
            </button>
        </form>
    </div>
</div>
@endsection
