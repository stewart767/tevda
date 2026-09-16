@extends('layouts.admin')

@section('title', 'Whistleblower Case: ' . $complaint->complaint_number)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cms.complaints.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono font-bold text-xs bg-slate-900 text-white px-2 py-0.5 rounded">{{ $complaint->complaint_number }}</span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $complaint->subject }}</h1>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Category: <strong>{{ ucwords(str_replace('_', ' ', $complaint->category)) }}</strong> • Logged: {{ $complaint->created_at->format('d M Y, H:i') }}
                </p>
            </div>
        </div>

        <div>
            @php
                $statusBadges = [
                    'submitted' => 'bg-blue-100 text-blue-800',
                    'under_review' => 'bg-amber-100 text-amber-800',
                    'investigation' => 'bg-orange-100 text-orange-800',
                    'resolved' => 'bg-emerald-100 text-emerald-800',
                    'closed' => 'bg-slate-100 text-slate-700',
                ];
            @endphp
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold {{ $statusBadges[$complaint->status] ?? 'bg-slate-100 text-slate-700' }}">
                Status: {{ ucwords(str_replace('_', ' ', $complaint->status)) }}
            </span>
        </div>
    </div>

    <!-- Case Details Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
        <!-- Metadata -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs pb-6 border-b border-slate-100">
            <div>
                <span class="text-slate-400 block mb-1">Whistleblower Identity</span>
                @if($complaint->is_anonymous)
                    <span class="font-bold text-amber-700"><i class="fa-solid fa-user-secret mr-1"></i> Anonymous Report</span>
                @else
                    <span class="font-bold text-slate-800">{{ $complaint->reporter_name ?? 'Confidential' }}</span>
                    <div class="text-[11px] text-slate-400">{{ $complaint->reporter_phone }}</div>
                @endif
            </div>

            <div>
                <span class="text-slate-400 block mb-1">Incident Date</span>
                <span class="font-bold text-slate-800">{{ $complaint->incident_date ? $complaint->incident_date->format('d M Y') : 'Not specified' }}</span>
            </div>

            <div>
                <span class="text-slate-400 block mb-1">Incident Location</span>
                <span class="font-bold text-slate-800">{{ $complaint->incident_location ?? 'Not specified' }}</span>
            </div>

            <div>
                <span class="text-slate-400 block mb-1">Severity Assessment</span>
                <span class="font-bold uppercase text-slate-800">{{ $complaint->severity ?? 'Normal' }}</span>
            </div>
        </div>

        <!-- Statement / Narrative -->
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Reported Grievance Narrative</h3>
            <div class="p-4 bg-slate-50 rounded-xl text-xs text-slate-700 leading-relaxed whitespace-pre-line border border-slate-100">
                {{ $complaint->description }}
            </div>
        </div>

        <!-- Evidence Attachment -->
        @if($complaint->evidence_file_path)
            <div class="p-4 bg-blue-50/50 border border-blue-100 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-paperclip text-blue-600 text-lg"></i>
                    <div>
                        <div class="font-bold text-xs text-blue-950">Submitted Evidence Document</div>
                        <div class="text-[11px] text-blue-700 font-mono">Gated Private Access</div>
                    </div>
                </div>
                <a href="{{ route('documents.complaint.download', $complaint->id) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition flex items-center gap-1">
                    <i class="fa-solid fa-download"></i> Download Evidence
                </a>
            </div>
        @endif
    </div>

    <!-- Case Investigation & Resolution Form -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
        <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
            <i class="fa-solid fa-gavel text-emerald-600"></i> Case Management & Resolution Desk
        </h2>

        <form method="POST" action="{{ route('admin.cms.complaints.update', $complaint->id) }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Update Investigation Status *</label>
                <select name="status" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-emerald-500">
                    <option value="submitted" {{ $complaint->status == 'submitted' ? 'selected' : '' }}>Submitted (Pending Triage)</option>
                    <option value="under_review" {{ $complaint->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                    <option value="assigned" {{ $complaint->status == 'assigned' ? 'selected' : '' }}>Assigned to Officer</option>
                    <option value="investigation" {{ $complaint->status == 'investigation' ? 'selected' : '' }}>Investigation In Progress</option>
                    <option value="resolved" {{ $complaint->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="closed" {{ $complaint->status == 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Internal Investigation Notes (Staff & Council Only)</label>
                <textarea name="internal_notes" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Internal findings, interview logs, officer notes...">{{ old('internal_notes', $complaint->internal_notes) }}</textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Public Resolution Summary (Visible to Whistleblower via Tracking Code)</label>
                <textarea name="resolution_summary" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs outline-none focus:ring-2 focus:ring-emerald-500" placeholder="Outcome communicated to reporter upon status check...">{{ old('resolution_summary', $complaint->resolution_summary) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-emerald-600/20 flex items-center gap-1.5">
                    <i class="fa-solid fa-check"></i> Save Case Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
