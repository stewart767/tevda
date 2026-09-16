@extends('layouts.admin')

@section('title', 'Projects & Beneficiary Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">EV Strategic Projects & Deployments</h1>
            <p class="text-sm text-slate-500">Oversee strategic programmes like the 50 Electric Three-Wheeler deployment and manage allocated beneficiaries.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-200">
                <i class="fa-solid fa-users-gear mr-1.5"></i> {{ $totalBeneficiaries }} Active Beneficiaries
            </span>
            <a href="{{ route('admin.projects.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-plus"></i> New Project
            </a>
        </div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Project Title</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Funding Status</th>
                        <th class="px-4 py-4">Project Status</th>
                        <th class="px-4 py-4">Beneficiaries</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($projects as $p)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs flex items-center gap-2">
                                    {{ $p->title }}
                                    @if($p->is_featured)
                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">FEATURED</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $p->summary }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700">
                                    {{ $p->category->name ?? 'Project' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $fundingBadges = [
                                        'proposal_under_development' => 'bg-amber-50 text-amber-800 border-amber-200',
                                        'seeking_partners' => 'bg-blue-50 text-blue-800 border-blue-200',
                                        'funding_approved' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                                        'partially_funded' => 'bg-teal-50 text-teal-800 border-teal-200',
                                        'to_be_confirmed' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $fundingBadges[$p->funding_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucwords(str_replace('_', ' ', $p->funding_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $projectBadges = [
                                        'proposal_under_development' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'open_for_applications' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'shortlisting_in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'active' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'completed' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $projectBadges[$p->project_status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucwords(str_replace('_', ' ', $p->project_status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div class="font-bold text-slate-800">{{ $p->beneficiaries->count() }} / {{ $p->target_beneficiaries_count }} Allocated</div>
                                <div class="text-[11px] text-slate-500 font-semibold">{{ $p->applications->count() }} Applied</div>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.projects.show', $p->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-list-check"></i> Manage Roster
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-folder-open text-3xl mb-2"></i>
                                <p>No projects registered.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($projects->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
