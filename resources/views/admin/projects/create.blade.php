@extends('layouts.admin')

@section('title', 'Register Strategic Project')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.projects.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900">Register Strategic EV Project</h1>
            <p class="text-xs text-slate-500">Configure funding status, target beneficiaries, pilot deployments, and upload project artwork.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form method="POST" action="{{ route('admin.projects.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Project Category *</label>
                    <select name="category_id" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">-- Select Category --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Target Beneficiary Count *</label>
                    <input type="number" name="target_beneficiaries_count" value="{{ old('target_beneficiaries_count', 50) }}" required min="1" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    @error('target_beneficiaries_count') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Project Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. 50 Electric Three-Wheeler Driver Empowerment Programme" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                @error('title') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Summary *</label>
                <textarea name="summary" rows="2" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Executive summary of the initiative...">{{ old('summary') }}</textarea>
                @error('summary') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Detailed Project Description *</label>
                <textarea name="description" rows="5" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Objectives, scope, asset deployment criteria, financing model...">{{ old('description') }}</textarea>
                @error('description') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Project Featured Image / Banner (JPG, PNG, WEBP)</label>
                <input type="file" name="featured_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-[11px] text-slate-400 mt-1">Recommended size: 1200x630px or high resolution landscape photo. Max 5MB.</p>
                @error('featured_image') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Funding Status *</label>
                    <select name="funding_status" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="proposal_under_development">Funding Proposal Under Development</option>
                        <option value="seeking_partners">Seeking Partners & Donors</option>
                        <option value="funding_approved">Funding Approved</option>
                        <option value="partially_funded">Partially Funded</option>
                        <option value="to_be_confirmed">To Be Confirmed</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Project Status *</label>
                    <select name="project_status" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="proposal_under_development">Proposal Under Development</option>
                        <option value="open_for_applications">Open for Member Applications</option>
                        <option value="applications_closed">Applications Closed</option>
                        <option value="shortlisting_in_progress">Shortlisting In Progress</option>
                        <option value="active">Active & Deployed</option>
                        <option value="completed">Completed</option>
                        <option value="paused">Paused</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Location / Target Region *</label>
                    <input type="text" name="location" value="{{ old('location', 'Dar es Salaam') }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Estimated Budget (TZS, Optional)</label>
                    <input type="number" name="budget_amount" value="{{ old('budget_amount') }}" step="10000" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Application Open Date</label>
                    <input type="date" name="application_open_date" value="{{ old('application_open_date') }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Application Close Date</label>
                    <input type="date" name="application_close_date" value="{{ old('application_close_date') }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Partner Organisations / Donors</label>
                <input type="text" name="partner_organisations" value="{{ old('partner_organisations') }}" placeholder="e.g. LATRA, MoT, Development Partners" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                    <span class="text-xs font-bold text-slate-700">Feature this project on homepage</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                <a href="{{ route('admin.projects.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/20 flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Register Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
