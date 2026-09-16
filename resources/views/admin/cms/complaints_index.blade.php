@extends('layouts.admin')

@section('title', 'Whistleblower & Grievance Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Whistleblower & Grievance Registry</h1>
            <p class="text-sm text-slate-500">Confidential investigations desk for ethics reports, misconduct, fraud, and road safety grievances.</p>
        </div>
        <div>
            <span class="inline-flex items-center px-3 py-2 rounded-xl bg-amber-50 text-amber-700 text-xs font-bold border border-amber-200">
                <i class="fa-solid fa-user-shield mr-1.5"></i> {{ $complaints->total() }} Total Cases
            </span>
        </div>
    </div>

    <!-- Complaints Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Tracking Number</th>
                        <th class="px-4 py-4">Category</th>
                        <th class="px-4 py-4">Subject & Incident Location</th>
                        <th class="px-4 py-4">Severity</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-4 py-4">Logged Date</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($complaints as $c)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-xs text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    {{ $c->complaint_number }}
                                </span>
                                @if($c->is_anonymous)
                                    <div class="text-[10px] text-amber-700 font-bold mt-1">ANONYMOUS</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs font-semibold text-slate-700">
                                {{ ucwords(str_replace('_', ' ', $c->category)) }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-700">
                                <div class="font-bold text-slate-900">{{ $c->subject }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5">
                                    <i class="fa-solid fa-location-dot text-slate-300 mr-1"></i>{{ $c->incident_location ?? 'Not specified' }}
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $sevColors = [
                                        'critical' => 'bg-rose-100 text-rose-800',
                                        'high' => 'bg-orange-100 text-orange-800',
                                        'medium' => 'bg-amber-100 text-amber-800',
                                        'low' => 'bg-slate-100 text-slate-700',
                                    ];
                                @endphp
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $sevColors[$c->severity] ?? 'bg-slate-100 text-slate-700' }}">
                                    {{ $c->severity ?? 'Normal' }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusBadges = [
                                        'submitted' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'under_review' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'assigned' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'investigation' => 'bg-orange-50 text-orange-700 border-orange-200',
                                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'closed' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $statusBadges[$c->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $c->status)) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400 whitespace-nowrap">
                                {{ $c->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.cms.complaints.show', $c->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-folder-open"></i> Case File
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-shield-halved text-3xl mb-2"></i>
                                <p>No grievance or whistleblower reports recorded.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($complaints->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $complaints->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
