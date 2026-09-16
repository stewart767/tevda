@extends('layouts.admin')

@section('title', 'Training Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Training Management & Capacity Building</h1>
            <p class="text-sm text-slate-500">Coordinate EV driver safety courses, manage scheduled cohorts, and grade assessments.</p>
        </div>
        <div>
            <a href="{{ route('admin.training.session.create') }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm shadow-emerald-600/20">
                <i class="fa-solid fa-calendar-plus"></i> Schedule New Cohort / Session
            </a>
        </div>
    </div>

    <!-- Quick Metrics -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-graduation-cap"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $programmes->count() }}</div>
                <div class="text-xs text-slate-500">Core Programmes</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-chalkboard-user"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $sessions->total() }}</div>
                <div class="text-xs text-slate-500">Total Cohorts</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-user-graduate"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $totalEnrolments }}</div>
                <div class="text-xs text-slate-500">Driver Enrolments</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-award"></i>
            </div>
            <div>
                <div class="text-2xl font-black text-slate-900">{{ $certificatesIssued }}</div>
                <div class="text-xs text-slate-500">Certificates Issued</div>
            </div>
        </div>
    </div>

    <!-- Active Programmes Overview -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm">
        <h2 class="text-base font-black text-slate-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-book-open text-emerald-600"></i> Official Curriculum Programmes
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($programmes as $prog)
                <div class="p-4 rounded-xl border border-slate-200 bg-slate-50/50 flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                                {{ $prog->code }}
                            </span>
                            <span class="text-xs font-semibold text-slate-500">{{ $prog->courses_count }} Modules / Courses</span>
                        </div>
                        <h3 class="font-black text-slate-900 text-sm mt-2">{{ $prog->title }}</h3>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2">{{ $prog->description }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Training Sessions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-calendar-days text-emerald-600"></i> Scheduled Training Cohorts & Sessions
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Cohort Code</th>
                        <th class="px-4 py-4">Course / Programme</th>
                        <th class="px-4 py-4">Location & Venue</th>
                        <th class="px-4 py-4">Schedule Dates</th>
                        <th class="px-4 py-4">Enrolments / Capacity</th>
                        <th class="px-4 py-4">Status</th>
                        <th class="px-5 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sessions as $s)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <span class="font-mono font-bold text-xs text-slate-900 bg-slate-100 px-2.5 py-1 rounded-lg">
                                    {{ $s->session_code }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-bold text-slate-900 text-xs">{{ $s->course->title ?? 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500">{{ $s->course->programme->title ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600">
                                <div><i class="fa-solid fa-location-dot text-emerald-600 mr-1"></i>{{ $s->location }}</div>
                                @if($s->venue)
                                    <div class="text-slate-400 text-[11px] mt-0.5">{{ $s->venue }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600 whitespace-nowrap">
                                <div><i class="fa-regular fa-calendar text-slate-400 mr-1"></i>{{ $s->start_date ? $s->start_date->format('d M Y') : 'TBD' }}</div>
                                @if($s->start_time)
                                    <div class="text-[11px] text-slate-400">{{ $s->start_time }} - {{ $s->end_time ?? '' }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs">
                                <div class="flex items-center gap-2">
                                    <span class="font-bold text-slate-800">{{ $s->enrolments->count() }} / {{ $s->capacity }}</span>
                                    @php
                                        $pct = $s->capacity > 0 ? round(($s->enrolments->count() / $s->capacity) * 100) : 0;
                                    @endphp
                                    <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500" style="width: {{ min(100, $pct) }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $sessionStatuses = [
                                        'scheduled' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'in_progress' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'cancelled' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $sessionStatuses[$s->status] ?? 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                    {{ ucfirst(str_replace('_', ' ', $s->status)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.training.session.show', $s->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-900 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold transition shadow-sm">
                                    <i class="fa-solid fa-list-check"></i> Manage Roster
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-chalkboard text-3xl mb-2"></i>
                                <p>No training cohorts scheduled yet. Click "Schedule New Cohort" to create one.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sessions->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $sessions->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
