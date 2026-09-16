@extends('layouts.admin')

@section('title', 'Membership Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Membership Management</h1>
            <p class="text-sm text-slate-500">Review driver registrations, verify identity & driving credentials, and issue memberships.</p>
        </div>
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <i class="fa-solid fa-users mr-1.5"></i> {{ $members->total() }} Total Registered
            </span>
            <a href="{{ route('admin.members.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-user-plus"></i> Register Member
            </a>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.members.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Search Keyword</label>
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, Phone, TEVDA No..." class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-slate-400 text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Category</label>
                <select name="category_id" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Status</label>
                <select name="status" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    <option value="">All Statuses</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted (Pending)</option>
                    <option value="payment_pending" {{ request('status') == 'payment_pending' ? 'selected' : '' }}>Payment Pending</option>
                    <option value="payment_confirmed" {{ request('status') == 'payment_confirmed' ? 'selected' : '' }}>Payment Confirmed</option>
                    <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved (Active)</option>
                    <option value="documents_incomplete" {{ request('status') == 'documents_incomplete' ? 'selected' : '' }}>Documents Incomplete</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Region</label>
                <select name="region_id" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 focus:bg-white outline-none">
                    <option value="">All Regions</option>
                    @foreach($regions as $reg)
                        <option value="{{ $reg->id }}" {{ request('region_id') == $reg->id ? 'selected' : '' }}>{{ $reg->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm shadow-emerald-600/20">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'category_id', 'status', 'region_id']))
                    <a href="{{ route('admin.members.index') }}" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-bold transition" title="Reset Filters">
                        <i class="fa-solid fa-rotate-right"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Applicant / Driver</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Location</th>
                        <th class="px-4 py-4">EV Details</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Registered</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($members as $m)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm shrink-0 uppercase">
                                        {{ substr($m->full_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-900 flex items-center gap-2">
                                            {{ $m->full_name }}
                                            @if($m->is_founding_member)
                                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800" title="Founding Member">FOUNDING</span>
                                            @endif
                                        </div>
                                        <div class="text-xs text-slate-500 flex items-center gap-2 mt-0.5">
                                            <span><i class="fa-solid fa-id-card text-slate-400 mr-1"></i>{{ $m->membership_number ?? 'Pending No.' }}</span>
                                            <span>•</span>
                                            <span><i class="fa-solid fa-phone text-slate-400 mr-1"></i>{{ $m->phone }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $m->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div><i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i>{{ $m->region->name ?? 'Tanzania' }}</div>
                                @if($m->district_name)
                                    <div class="text-slate-400 mt-0.5">{{ $m->district_name }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                @if($m->primaryVehicle)
                                    <div class="font-semibold text-slate-800">{{ ucfirst($m->primaryVehicle->vehicle_type) }}</div>
                                    <div class="text-slate-400 font-mono">{{ $m->primaryVehicle->registration_number }}</div>
                                @else
                                    <span class="text-slate-400 italic">No EV logged</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusClasses = [
                                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'under_review' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'payment_pending' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'payment_confirmed' => 'bg-teal-50 text-teal-700 border-teal-200',
                                        'documents_incomplete' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'suspended' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $statusClasses[$m->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                    {{ ucwords(str_replace('_', ' ', $m->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $m->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="inline-flex items-center gap-1.5 justify-end">
                                    @if($m->membership_number && $m->status === 'approved')
                                        <a href="{{ route('admin.members.card.download', $m->id) }}" class="p-1.5 text-slate-500 hover:text-emerald-600 hover:bg-emerald-50 rounded-xl transition" title="Download ID Card PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.members.edit', $m->id) }}" class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition" title="Edit Member Information">
                                        <i class="fa-solid fa-pen-to-square text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.members.show', $m->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                        <i class="fa-solid fa-folder-open"></i> Review
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400">
                                <i class="fa-solid fa-user-slash text-3xl mb-2"></i>
                                <p class="text-sm font-medium">No members found matching the specified filters.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($members->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $members->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
