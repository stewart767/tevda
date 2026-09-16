@extends('layouts.admin')

@section('title', 'Opportunities Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Driver Opportunities & Jobs</h1>
            <p class="text-sm text-slate-500">Manage EV fleet driver placements, charging infrastructure franchises, and vehicle financing opportunities.</p>
        </div>
        <div>
            <a href="{{ route('admin.opportunities.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-plus"></i> Post New Opportunity
            </a>
        </div>
    </div>

    <!-- Opportunities Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Opportunity</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Provider / Partner</th>
                        <th class="px-4 py-4">Location & Deadline</th>
                        <th class="px-4 py-4">Applications</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($opportunities as $opp)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs flex items-center gap-2">
                                    {{ $opp->title }}
                                    @if($opp->is_featured)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">FEATURED</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $opp->summary }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $opp->category->name ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs font-bold text-slate-800">
                                {{ $opp->provider_name }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div><i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i>{{ $opp->location }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    Deadline: {{ $opp->deadline ? $opp->deadline->format('d M Y') : 'Ongoing' }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <span class="font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                                    {{ $opp->applications->count() }} Applicants
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusBadge = [
                                        'published' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'draft' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'closed' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        'archived' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusBadge[$opp->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucfirst($opp->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.opportunities.show', $opp->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-users-line"></i> Manage Applications
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-briefcase text-3xl mb-2"></i>
                                <p>No opportunities created yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($opportunities->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $opportunities->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
