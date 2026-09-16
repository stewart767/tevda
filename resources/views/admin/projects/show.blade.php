@extends('layouts.admin')

@section('title', 'Project Management: ' . $project->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.projects.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $project->title }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {{ ucwords(str_replace('_', ' ', $project->project_status)) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Category: <strong>{{ $project->category->name ?? 'Initiative' }}</strong> • Target Beneficiaries: <strong>{{ $project->target_beneficiaries_count }}</strong>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white">
                {{ $project->beneficiaries->count() }} Allocated / {{ $project->applications->count() }} Applicants
            </span>
        </div>
    </div>

    <!-- Quick Status Update Bar -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm">
        <form method="POST" action="{{ route('admin.projects.status', $project->id) }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Project Deployment Status</label>
                <select name="project_status" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="proposal_under_development" {{ $project->project_status == 'proposal_under_development' ? 'selected' : '' }}>Proposal Under Development</option>
                    <option value="open_for_applications" {{ $project->project_status == 'open_for_applications' ? 'selected' : '' }}>Open for Applications</option>
                    <option value="applications_closed" {{ $project->project_status == 'applications_closed' ? 'selected' : '' }}>Applications Closed</option>
                    <option value="shortlisting_in_progress" {{ $project->project_status == 'shortlisting_in_progress' ? 'selected' : '' }}>Shortlisting In Progress</option>
                    <option value="active" {{ $project->project_status == 'active' ? 'selected' : '' }}>Active & Deployed</option>
                    <option value="completed" {{ $project->project_status == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="paused" {{ $project->project_status == 'paused' ? 'selected' : '' }}>Paused</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Official Funding Status</label>
                <select name="funding_status" class="w-full py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="proposal_under_development" {{ $project->funding_status == 'proposal_under_development' ? 'selected' : '' }}>Proposal Under Development</option>
                    <option value="seeking_partners" {{ $project->funding_status == 'seeking_partners' ? 'selected' : '' }}>Seeking Partners</option>
                    <option value="funding_approved" {{ $project->funding_status == 'funding_approved' ? 'selected' : '' }}>Funding Approved</option>
                    <option value="partially_funded" {{ $project->funding_status == 'partially_funded' ? 'selected' : '' }}>Partially Funded</option>
                    <option value="to_be_confirmed" {{ $project->funding_status == 'to_be_confirmed' ? 'selected' : '' }}>To Be Confirmed</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full py-2 px-4 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="fa-solid fa-arrows-rotate"></i> Update Project Status
                </button>
            </div>
        </form>
    </div>

    <!-- Active Beneficiary Allocations Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-users-gear text-emerald-600"></i> Allocated Beneficiaries & Asset Handover Roster
            </h2>
            <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-100">
                {{ $project->beneficiaries->count() }} Allocated
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Beneficiary Code</th>
                        <th class="px-4 py-4">Beneficiary Driver</th>
                        <th class="px-4 py-4">Allocated EV / Asset</th>
                        <th class="px-4 py-4">Allocation Date</th>
                        <th class="px-4 py-4">Training Status</th>
                        <th class="px-4 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($project->beneficiaries as $ben)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-xs text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    {{ $ben->beneficiary_code }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $ben->member->full_name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $ben->member->membership_number ?? 'Member' }} • {{ $ben->member->phone }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div class="font-bold text-slate-800">{{ $ben->asset_type }}</div>
                                <div class="font-mono text-slate-500 text-[11px]">{{ $ben->asset_registration_or_serial }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $ben->allocation_date ? $ben->allocation_date->format('d M Y') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4">
                                @if($ben->training_completed)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        <i class="fa-solid fa-check mr-1"></i> Certified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">
                                        Pending Training
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    {{ ucfirst($ben->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-hand-holding-hand text-3xl mb-2"></i>
                                <p>No beneficiaries allocated to this project yet. Review member applications below to shortlist and allocate.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Candidate Member Applications Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-id-card-clip text-emerald-600"></i> Applicant Drivers & Shortlisting
            </h2>
            <span class="text-xs text-slate-500">{{ $project->applications->count() }} Total Applications</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Applicant Driver</th>
                        <th class="px-4 py-4">Application No</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Statement</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($project->applications as $app)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $app->member->full_name }}</div>
                                <div class="text-[11px] text-slate-500">{{ $app->member->membership_number ?? 'Member' }} • {{ $app->member->phone }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $app->application_number }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $app->status === 'asset_allocated' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : ($app->status === 'shortlisted' ? 'bg-blue-50 text-blue-700 border-blue-200' : 'bg-slate-100 text-slate-700 border-slate-200') }}">
                                    {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600 max-w-xs">
                                <p class="line-clamp-2 italic">{{ $app->statement ?? 'No statement provided' }}</p>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($app->status !== 'shortlisted' && $app->status !== 'asset_allocated')
                                        <form method="POST" action="{{ route('admin.projects.applications.shortlist', $app->id) }}">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">
                                                Shortlist
                                            </button>
                                        </form>
                                    @endif

                                    @if($app->status !== 'asset_allocated')
                                        <button type="button" onclick="openAllocateModal('{{ $app->id }}', '{{ addslashes($app->member->full_name) }}')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                                            Allocate Asset
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                                <p>No applications received for this project yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Allocate Beneficiary Asset -->
<div id="allocateModal" class="hidden fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl space-y-4 border border-slate-200">
        <div class="flex items-center justify-between">
            <h3 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-truck-ramp-box text-emerald-600"></i> Allocate Beneficiary Asset
            </h3>
            <button type="button" onclick="document.getElementById('allocateModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <p class="text-xs text-slate-500">
            Allocate vehicle/hardware asset to candidate: <strong id="modalApplicantName" class="text-slate-800"></strong>. This will generate a formal beneficiary tracking code.
        </p>
        <form id="allocateForm" method="POST" action="" class="space-y-3">
            @csrf
            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Asset Type *</label>
                <input type="text" name="asset_type" value="Electric Three-Wheeler (Bajaji)" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Asset Registration / Serial Number *</label>
                <input type="text" name="asset_registration_or_serial" required placeholder="e.g. MC 450 DRZ or Chassis No" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-600 mb-1">Allocation / Handover Date *</label>
                <input type="date" name="allocation_date" value="{{ date('Y-m-d') }}" required class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500">
            </div>

            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="training_completed" value="1" checked class="w-4 h-4 text-emerald-600 rounded">
                    <span class="text-xs font-bold text-slate-700">Driver has completed prerequisite EV safety training</span>
                </label>
            </div>

            <div class="flex items-center justify-end gap-2 pt-4 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('allocateModal').classList.add('hidden')" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-bold">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-600/20">
                    Confirm Handover
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAllocateModal(appId, memberName) {
    document.getElementById('modalApplicantName').textContent = memberName;
    document.getElementById('allocateForm').action = "/admin/projects/applications/" + appId + "/allocate";
    document.getElementById('allocateModal').classList.remove('hidden');
}
</script>
@endsection
