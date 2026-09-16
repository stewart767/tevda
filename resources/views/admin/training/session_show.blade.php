@extends('layouts.admin')

@section('title', 'Training Cohort: ' . $session->session_code)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.training.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <span class="font-mono font-bold text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded">{{ $session->session_code }}</span>
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $session->course->title }}</h1>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    {{ $session->course->programme->title ?? 'Training Programme' }} • Location: <strong>{{ $session->location }}</strong>
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white">
                {{ $session->enrolments->count() }} / {{ $session->capacity }} Enrolled
            </span>
        </div>
    </div>

    <!-- Cohort Summary Info Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
        <div>
            <span class="text-slate-400 block mb-1">Trainer / Instructor</span>
            <span class="font-bold text-slate-800">{{ $session->trainer_name ?? 'Assigned Instructor' }}</span>
        </div>
        <div>
            <span class="text-slate-400 block mb-1">Dates & Duration</span>
            <span class="font-bold text-slate-800">{{ $session->start_date ? $session->start_date->format('d M Y') : 'TBD' }}</span>
        </div>
        <div>
            <span class="text-slate-400 block mb-1">Venue</span>
            <span class="font-bold text-slate-800">{{ $session->venue ?? $session->location }}</span>
        </div>
        <div>
            <span class="text-slate-400 block mb-1">Course Pass Mark</span>
            <span class="font-bold text-emerald-600">{{ $session->course->pass_mark_percentage ?? 70 }}% Score</span>
        </div>
    </div>

    <!-- Main Enrolment & Assessment Roster -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-users-viewfinder text-emerald-600"></i> Trainee Enrolment Roster & Grading
                </h2>
                <p class="text-xs text-slate-500">Record attendance check-ins and submit assessment scores to automatically issue verified certificates.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Trainee / Driver</th>
                        <th class="px-4 py-4">Enrolment Date</th>
                        <th class="px-4 py-4">Attendance Check-in</th>
                        <th class="px-4 py-4">Assessment Evaluation</th>
                        <th class="px-4 py-4">Status & Certificate</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($session->enrolments as $enr)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $enr->member->full_name }}</div>
                                <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-slate-600">{{ $enr->member->membership_number ?? 'Driver' }}</span>
                                    <span>•</span>
                                    <span>{{ $enr->member->phone }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $enr->enrolment_date ? $enr->enrolment_date->format('d M Y') : $enr->created_at->format('d M Y') }}
                            </td>
                            <td class="px-4 py-4">
                                <!-- Attendance Form -->
                                <form method="POST" action="{{ route('admin.training.session.attendance', $session->id) }}" class="flex items-center gap-1.5">
                                    @csrf
                                    <input type="hidden" name="enrolment_id" value="{{ $enr->id }}">
                                    <input type="date" name="attendance_date" value="{{ date('Y-m-d') }}" class="py-1 px-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none focus:ring-1 focus:ring-emerald-500">
                                    <select name="status" class="py-1 px-2 bg-slate-50 border border-slate-200 rounded-lg text-xs outline-none">
                                        <option value="present">Present</option>
                                        <option value="absent">Absent</option>
                                        <option value="excused">Excused</option>
                                    </select>
                                    <button type="submit" class="p-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs transition" title="Log Attendance">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                <div class="text-[10px] text-slate-400 mt-1">
                                    Total logs: {{ $enr->attendances->count() }} ({{ $enr->attendances->where('status', 'present')->count() }} Present)
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <!-- Assessment Score Form -->
                                <form method="POST" action="{{ route('admin.training.session.results', $session->id) }}" class="flex items-center gap-1.5">
                                    @csrf
                                    <input type="hidden" name="enrolment_id" value="{{ $enr->id }}">
                                    <input type="number" name="score" min="0" max="100" step="0.5" value="{{ $enr->result?->score }}" placeholder="Score (0-100)" class="w-24 py-1 px-2.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono font-bold outline-none focus:ring-1 focus:ring-emerald-500">
                                    <button type="submit" class="px-2.5 py-1 bg-slate-900 hover:bg-emerald-600 text-white rounded-lg text-xs font-bold transition">
                                        Submit Score
                                    </button>
                                </form>
                                @if($enr->result)
                                    <div class="text-[11px] mt-1 font-semibold {{ $enr->result->status === 'pass' ? 'text-emerald-700' : 'text-rose-600' }}">
                                        Result: {{ $enr->result->score }}% ({{ strtoupper($enr->result->status) }})
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if($enr->result && $enr->result->certificate)
                                    <div class="space-y-1">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-award mr-1"></i> Certified
                                        </span>
                                        <div class="text-[11px] font-mono text-slate-600">
                                            <a href="{{ route('admin.certificates.show', $enr->result->certificate->id) }}" class="text-emerald-600 hover:underline">
                                                {{ $enr->result->certificate->certificate_number }}
                                            </a>
                                        </div>
                                    </div>
                                @elseif($enr->status === 'failed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">
                                        Failed Assessment
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600">
                                        {{ ucfirst($enr->status) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-user-group text-3xl mb-2"></i>
                                <p>No candidates enrolled in this cohort yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
