@extends('layouts.portal')

@section('title', 'EV Projects & Beneficiaries — TEVDA Member Portal')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-slate-900 font-heading">EV Deployment Projects & Asset Allocation</h1>
        <p class="text-xs text-slate-500">Track strategic commercial EV rollout initiatives, pilot asset allocations, and submitted beneficiary applications.</p>
    </div>

    <!-- My Project Applications -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 font-heading">My Beneficiary Applications</h2>

        <div class="space-y-3">
            @forelse ($myApplications as $app)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full">{{ $app->project->category->name }}</span>
                        <h3 class="font-bold text-sm text-slate-900 mt-1">{{ $app->project->title }}</h3>
                        <p class="text-xs font-mono text-slate-500">{{ $app->application_number }} • Submitted {{ $app->created_at->format('d M Y') }}</p>
                        @if ($app->beneficiary)
                            <div class="p-3 bg-emerald-100/70 border border-emerald-300 rounded-xl text-xs text-emerald-950 mt-2">
                                <strong>Asset Allocated:</strong> {{ $app->beneficiary->asset_registration_or_serial }} (Code: {{ $app->beneficiary->beneficiary_code }})
                            </div>
                        @endif
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full uppercase {{ $app->status === 'asset_allocated' ? 'bg-emerald-600 text-white' : ($app->status === 'shortlisted' ? 'bg-indigo-100 text-indigo-800' : 'bg-slate-200 text-slate-700') }}">
                        {{ strtoupper(str_replace('_', ' ', $app->status)) }}
                    </span>
                </div>
            @empty
                <p class="text-xs text-slate-500 py-4 text-center">You have no active project applications.</p>
            @endforelse
        </div>
    </div>

    <!-- Available Projects -->
    <div class="space-y-6">
        <h2 class="text-lg font-bold text-slate-900 font-heading">Association EV Projects</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($projects as $proj)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-emerald-500 transition" x-data="{ applyOpen: false }">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">{{ $proj->category->name }}</span>
                            <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $proj->project_status === 'open_for_applications' ? 'bg-emerald-600 text-white' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucwords(str_replace('_', ' ', $proj->project_status)) }}
                            </span>
                        </div>
                        <h3 class="font-bold text-base text-slate-900 mb-1">{{ $proj->title }}</h3>
                        <p class="text-xs text-slate-500 mb-4">{{ $proj->location }} • Target: {{ $proj->target_beneficiaries_count }} Beneficiaries</p>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-2">{{ $proj->summary }}</p>
                    </div>

                    <div>
                        @if ($proj->project_status === 'open_for_applications')
                            <button type="button" @click="applyOpen = !applyOpen" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition">
                                Apply for Beneficiary Selection
                            </button>

                            <!-- Application Form Modal -->
                            <div x-show="applyOpen" x-transition class="mt-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                                <form action="{{ route('portal.projects.apply', $proj->id) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Statement of Need / Commercial Operations Route <span class="text-rose-500">*</span></label>
                                        <textarea name="statement_of_need" rows="3" required placeholder="Describe your daily operating route, driving experience, and readiness for EV deployment..." class="w-full bg-white border border-slate-300 rounded-xl p-2.5 text-xs focus:outline-hidden focus:border-emerald-500"></textarea>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Operating Zone</label>
                                        <input type="text" name="operating_zone_or_route" placeholder="e.g. Ubungo - Posta corridor" class="w-full bg-white border border-slate-300 rounded-xl p-2 text-xs">
                                    </div>
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2 rounded-xl transition">
                                        Submit Application
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-center text-xs text-slate-500 font-medium">
                                Applications currently pending confirmation
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
