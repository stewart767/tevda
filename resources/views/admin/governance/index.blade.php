@extends('layouts.admin')

@section('title', 'Governance & Leadership Administration')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Governance & Association Structure</h1>
            <p class="text-xs sm:text-sm text-slate-500">Manage confirmed executive leadership, regional branches, and institutional partner logos.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" onclick="document.getElementById('partnerModal').classList.remove('hidden')" class="px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 border border-indigo-200 rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-xs">
                <i class="fa-solid fa-handshake"></i> Register Partner
            </button>
            <button type="button" onclick="document.getElementById('branchModal').classList.remove('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold transition flex items-center gap-1.5">
                <i class="fa-solid fa-code-branch"></i> Register Branch
            </button>
            <a href="{{ route('admin.governance.leaders.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-user-plus"></i> Add Leader
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-xs font-bold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Founding Executive Leadership Showcase -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-landmark text-emerald-600"></i> Confirmed Founding & Executive Leaders
            </h2>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                {{ $leaders->count() }} Leaders Registered
            </span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($leaders as $leader)
                <div class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 space-y-3 relative">
                    @if($leader->is_founding_leader)
                        <span class="absolute top-4 right-4 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            FOUNDING
                        </span>
                    @endif

                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-emerald-700 text-white font-black text-base flex items-center justify-center shrink-0 uppercase overflow-hidden">
                            @if($leader->photo_url)
                                <img src="{{ $leader->photo_url }}" alt="{{ $leader->name }}" class="w-full h-full object-cover rounded-xl" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <span class="hidden">{{ substr($leader->name, 0, 2) }}</span>
                            @else
                                {{ substr($leader->name, 0, 2) }}
                            @endif
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 text-sm">{{ $leader->name }}</h3>
                            <p class="text-xs font-bold text-emerald-700">{{ $leader->position }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-slate-500 line-clamp-2">{{ $leader->biography ?? 'Official executive leadership representative.' }}</p>

                    <div class="text-[11px] text-slate-400 pt-2 border-t border-slate-200 flex items-center justify-between">
                        <span>{{ $leader->governanceBody->name ?? 'Executive Council' }}</span>
                        <span>{{ $leader->official_office_contact ?? 'info@tevda.or.tz' }}</span>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-8 text-xs text-slate-400">
                    No leadership profiles registered.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Strategic Stakeholders & Partners Grid with Logo Showcase -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-handshake text-emerald-600"></i> Strategic Stakeholders & Partner Network
            </h2>
            <span class="text-xs font-bold text-indigo-700 bg-indigo-50 px-2.5 py-1 rounded-lg border border-indigo-100">
                {{ $partners->count() }} Active Partners
            </span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
            @forelse($partners as $partner)
                <div class="p-4 rounded-2xl border border-slate-200 bg-slate-50/60 flex flex-col items-center justify-center text-center space-y-2">
                    <div class="w-14 h-14 rounded-xl bg-white p-2 border border-slate-200 flex items-center justify-center overflow-hidden shadow-2xs">
                        @if($partner->logo_url)
                            <img src="{{ $partner->logo_url }}" alt="{{ $partner->name }}" class="max-h-10 max-w-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                            <span class="hidden font-black text-xs text-slate-700">{{ strtoupper(substr($partner->name, 0, 3)) }}</span>
                        @else
                            <span class="font-black text-xs text-slate-700">{{ strtoupper(substr($partner->name, 0, 3)) }}</span>
                        @endif
                    </div>
                    <div>
                        <h4 class="font-bold text-xs text-slate-900">{{ $partner->name }}</h4>
                        <p class="text-[10px] text-slate-500">{{ ucwords(str_replace('_', ' ', $partner->category)) }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-4 text-center py-6 text-xs text-slate-400">
                    No partners registered yet. Click "Register Partner" above.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Two Column: Regional Branches & Strategic Partners Enquiries -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Branches -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-600"></i> Association Regional Branches
                </h2>
                <span class="text-xs font-bold text-slate-500">{{ $branches->count() }} Configured</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">Branch Name</th>
                            <th class="px-3 py-3">Region</th>
                            <th class="px-3 py-3">Contact</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($branches as $b)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $b->name }}
                                    <div class="text-[10px] text-slate-400 font-mono">{{ $b->code }}</div>
                                </td>
                                <td class="px-3 py-3 text-slate-600">{{ $b->region->name ?? 'Tanzania' }}</td>
                                <td class="px-3 py-3 text-slate-600">{{ $b->contact_phone ?? 'info@tevda.or.tz' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $b->status === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ ucfirst($b->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">No branches added.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Partnership Enquiries -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-inbox text-emerald-600"></i> Partnership Inquiries
                </h2>
                <span class="text-xs font-bold text-slate-500">{{ $enquiries->count() }} Recent</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 border-b border-slate-200 font-bold text-slate-500 uppercase">
                        <tr>
                            <th class="px-4 py-3">Organisation</th>
                            <th class="px-3 py-3">Category</th>
                            <th class="px-3 py-3">Contact</th>
                            <th class="px-4 py-3">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($enquiries as $enq)
                            <tr class="hover:bg-slate-50">
                                <td class="px-4 py-3 font-bold text-slate-900">
                                    {{ $enq->organisation_name }}
                                </td>
                                <td class="px-3 py-3 text-slate-600">{{ ucwords(str_replace('_', ' ', $enq->category ?? 'General')) }}</td>
                                <td class="px-3 py-3 text-slate-600">{{ $enq->contact_person }} ({{ $enq->email }})</td>
                                <td class="px-4 py-3 text-slate-400 whitespace-nowrap">{{ $enq->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-slate-400">No partnership inquiries received yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Register Branch -->
<div id="branchModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-code-branch text-emerald-600"></i> Register Regional Branch
            </h3>
            <button type="button" onclick="document.getElementById('branchModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.governance.branches.store') }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Branch Name *</label>
                <input type="text" name="name" required placeholder="e.g. Dar es Salaam Coastal Branch" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Branch Code *</label>
                    <input type="text" name="code" required placeholder="e.g. BR-DSM-01" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Region *</label>
                    <select name="region_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach(\App\Models\Region::all() as $r)
                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Contact Phone</label>
                    <input type="text" name="contact_phone" placeholder="+255 7..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                    <select name="status" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none">
                        <option value="proposed">Proposed</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('branchModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20">
                    Save Branch
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Register Partner -->
<div id="partnerModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-handshake text-emerald-600"></i> Register Strategic Partner
            </h3>
            <button type="button" onclick="document.getElementById('partnerModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('admin.governance.partners.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Partner / Organisation Name *</label>
                <input type="text" name="name" required placeholder="e.g. LATRA, E-Motion Africa, CRDB Green Finance" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Category *</label>
                    <select name="category" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                        <option value="government_and_authorities">Government / Regulatory</option>
                        <option value="financial_institutions">Financial Institution</option>
                        <option value="ev_companies">EV Manufacturer / Swapper</option>
                        <option value="colleges_and_research">College / Research</option>
                        <option value="development_organisations">Development NGO</option>
                        <option value="employers_and_logistics">Logistics / Fleet</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-600 mb-1">Website URL</label>
                    <input type="url" name="website_url" placeholder="https://..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Official Partner Logo (PNG, SVG, JPG, WEBP)</label>
                <input type="file" name="logo" accept="image/*" class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Description / Collaboration Summary</label>
                <textarea name="description" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none" placeholder="Brief summary of collaboration area..."></textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('partnerModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20">
                    Save Partner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
