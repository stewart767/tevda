@extends('layouts.admin')

@section('title', 'Edit Leader — ' . $leader->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.governance.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 font-heading">Edit Leadership Profile</h1>
            <p class="text-xs text-slate-500">Update executive role, bio, and portfolio details for {{ $leader->name }}.</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 sm:p-8 border border-slate-200/80 shadow-sm">
        <form method="POST" action="{{ route('admin.governance.leaders.update', $leader->id) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Photo & Upload -->
            <div class="flex items-center gap-5 p-4 bg-slate-50 rounded-2xl border border-slate-200/80">
                <div class="w-20 h-20 rounded-2xl bg-slate-200 overflow-hidden shrink-0 flex items-center justify-center text-slate-500 font-bold border-2 border-white shadow-xs">
                    @if ($leader->photo_url)
                        <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-2xl font-black text-slate-400">{{ strtoupper(substr($leader->name, 0, 2)) }}</span>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Update Portrait Photo</label>
                    <input type="file" name="photo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                    <span class="text-[11px] text-slate-400 block mt-1">Recommended square format (PNG, JPG, WEBP, max 3MB).</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Full Name & Title *</label>
                    <input type="text" name="name" value="{{ old('name', $leader->name) }}" required placeholder="e.g. Sharifa Abdallah" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    @error('name') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Executive Position / Role *</label>
                    <input type="text" name="position" value="{{ old('position', $leader->position) }}" required placeholder="e.g. Mkurugenzi Mtendaji" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    @error('position') <p class="text-rose-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Governance Organ / Body</label>
                    <select name="governance_body_id" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">-- Select Body (Optional) --</option>
                        @foreach($bodies as $b)
                            <option value="{{ $b->id }}" {{ old('governance_body_id', $leader->governance_body_id) == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Official Contact / Email</label>
                    <input type="text" name="official_office_contact" value="{{ old('official_office_contact', $leader->official_office_contact) }}" placeholder="e.g. ed@tevda.or.tz" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Display Order Ranking</label>
                    <input type="number" name="order_number" value="{{ old('order_number', $leader->order_number) }}" min="1" max="99" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Professional Biography</label>
                <textarea name="biography" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Background, leadership profile and strategic oversight...">{{ old('biography', $leader->biography) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Key Responsibilities / Portfolio Mandate</label>
                <textarea name="responsibilities" rows="2" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="Key organizational responsibilities and institutional duties...">{{ old('responsibilities', $leader->responsibilities) }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Sector Experience & Qualifications</label>
                    <input type="text" name="sector_experience" value="{{ old('sector_experience', $leader->sector_experience) }}" placeholder="e.g. Strategic Management & Sustainable Transport" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Term Period</label>
                    <input type="text" name="term_period" value="{{ old('term_period', $leader->term_period ?? '2024 - Present') }}" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer">
                    <input type="checkbox" name="is_founding_leader" value="1" {{ old('is_founding_leader', $leader->is_founding_leader) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Designate as Founding Leader</span>
                        <span class="text-[11px] text-slate-500">Highlight in Founding Leadership section</span>
                    </div>
                </label>

                <label class="flex items-center gap-2.5 p-3 rounded-xl border border-slate-200 bg-slate-50/50 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $leader->is_active) ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded">
                    <div>
                        <span class="text-xs font-bold text-slate-800 block">Active Status</span>
                        <span class="text-[11px] text-slate-500">Visible on public leadership page</span>
                    </div>
                </label>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-slate-100">
                <button type="button" 
                        onclick="if(confirm('Are you sure you want to delete this leadership profile permanently?')) document.getElementById('delete-leader-form-{{ $leader->id }}').submit();"
                        class="px-4 py-2.5 text-xs font-bold text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-xl transition flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Delete Leader</span>
                </button>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.governance.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Update Leader Profile</span>
                    </button>
                </div>
            </div>
        </form>

        <form id="delete-leader-form-{{ $leader->id }}" action="{{ route('admin.governance.leaders.destroy', $leader->id) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    </div>
</div>
@endsection
