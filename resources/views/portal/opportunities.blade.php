@extends('layouts.portal')

@section('title', 'Opportunities & Applications — TEVDA Member Portal')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">Grants, Finance & Employment Opportunities</h1>
        <p class="text-xs text-slate-500">Apply for verified transition grants, asset financing, jobs, and commercial transport contracts.</p>
    </div>

    <!-- My Applications -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 font-heading">My Submitted Applications</h2>

        <div class="space-y-3">
            @forelse ($myApplications as $app)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded-full">{{ $app->opportunity->category->name }}</span>
                        <h3 class="font-bold text-sm text-slate-900 mt-1">{{ $app->opportunity->title }}</h3>
                        <p class="text-xs font-mono text-slate-500">{{ $app->application_number }} • Submitted {{ $app->created_at->format('d M Y') }}</p>
                        @if ($app->admin_feedback)
                            <p class="text-xs text-emerald-800 bg-emerald-50 p-2 rounded-lg border border-emerald-200 mt-2"><strong>Admin Feedback:</strong> {{ $app->admin_feedback }}</p>
                        @endif
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full uppercase {{ $app->status === 'accepted' ? 'bg-emerald-100 text-emerald-800' : ($app->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
                        {{ strtoupper(str_replace('_', ' ', $app->status)) }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-500 py-4 text-center">You have not submitted applications for any opportunities yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Opportunities List -->
    <div class="space-y-6">
        <h2 class="text-lg font-bold text-slate-900 font-heading">Available Opportunities</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($opportunities as $opp)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-indigo-500 transition" x-data="{ applyOpen: false }">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] uppercase font-bold text-indigo-700 bg-indigo-50 px-2.5 py-0.5 rounded-full">{{ $opp->category->name }}</span>
                            @if ($opp->deadline)
                                <span class="text-xs text-slate-500">Deadline: {{ $opp->deadline->format('d M Y') }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-base text-slate-900 mb-1">{{ $opp->title }}</h3>
                        <p class="text-xs text-slate-500 mb-4">{{ $opp->provider_name }} • {{ $opp->location }}</p>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-2">{{ $opp->summary }}</p>
                    </div>

                    <div>
                        @if ($opp->application_type === 'external')
                            <a href="{{ $opp->external_url }}" target="_blank" class="w-full text-center bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition block">
                                Apply via Official Channel &rarr;
                            </a>
                        @else
                            <button type="button" @click="applyOpen = !applyOpen" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition">
                                Apply Now (Internal)
                            </button>

                            <!-- Inline Application Modal / Dropdown -->
                            <div x-show="applyOpen" x-transition class="mt-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                                <form action="{{ route('portal.opportunities.apply', $opp->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Cover Note / Statement of Interest</label>
                                        <textarea name="cover_letter" rows="3" required placeholder="Explain why you are qualified..." class="w-full bg-white border border-slate-300 rounded-xl p-2.5 text-xs focus:outline-hidden focus:border-indigo-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Attach Resume / Credentials (Optional)</label>
                                        <input type="file" name="resume" class="text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:bg-slate-200">
                                    </div>
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 rounded-xl transition">
                                        Submit Application
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
