@extends('layouts.admin')

@section('title', 'Opportunity Applications: ' . $opportunity->title)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.opportunities.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-900">{{ $opportunity->title }}</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                        {{ ucfirst($opportunity->status) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                    Provider: <strong>{{ $opportunity->provider_name }}</strong> • Category: {{ $opportunity->category->name ?? 'General' }}
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white">
                {{ $opportunity->applications->count() }} Total Applications
            </span>
        </div>
    </div>

    <!-- Opportunity Details Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-3 text-xs">
        <div class="font-bold text-slate-400 uppercase tracking-wider">Opportunity Summary & Eligibility</div>
        <p class="text-slate-700 leading-relaxed">{{ $opportunity->description }}</p>
        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-slate-600">
            <strong>Requirements:</strong> {{ $opportunity->eligibility_criteria }}
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i class="fa-solid fa-users-gear text-emerald-600"></i> Member Application Submissions
            </h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-5 py-4">Applicant Member</th>
                        <th class="px-4 py-4">Application Code</th>
                        <th class="px-4 py-4">Applied Date</th>
                        <th class="px-4 py-4">Cover Statement</th>
                        <th class="px-4 py-4">Status & Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($opportunity->applications as $app)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="px-5 py-4">
                                <div class="font-black text-slate-900 text-xs">{{ $app->member->full_name }}</div>
                                <div class="text-[11px] text-slate-500 flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-slate-600">{{ $app->member->membership_number ?? 'Member' }}</span>
                                    <span>•</span>
                                    <span>{{ $app->member->phone }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-mono text-xs font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $app->application_number }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-500 whitespace-nowrap">
                                {{ $app->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-600 max-w-xs">
                                <p class="line-clamp-2 italic">{{ $app->cover_note ?? 'No cover statement' }}</p>
                            </td>
                            <td class="px-4 py-4">
                                <!-- Status update form -->
                                <form method="POST" action="{{ route('admin.opportunities.applications.status', $app->id) }}" class="space-y-1.5">
                                    @csrf
                                    <div class="flex items-center gap-1.5">
                                        <select name="status" class="py-1 px-2 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold outline-none">
                                            <option value="submitted" {{ $app->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                            <option value="under_review" {{ $app->status == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                            <option value="shortlisted" {{ $app->status == 'shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                                            <option value="accepted" {{ $app->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="rejected" {{ $app->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                        <button type="submit" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                                            Save
                                        </button>
                                    </div>
                                    <input type="text" name="feedback" value="{{ $app->admin_feedback }}" placeholder="Feedback note to applicant..." class="w-full py-1 px-2 bg-slate-50 border border-slate-200 rounded-lg text-[11px] outline-none">
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-slate-400 text-xs">
                                <i class="fa-solid fa-inbox text-3xl mb-2"></i>
                                <p>No applications received for this opportunity yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
