@extends('layouts.portal')

@section('title', 'Opportunities & Applications — TEVDA Member Portal')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div>
        <h1 class="text-2xl font-bold text-[#002040] font-heading">Grants, Finance & Employment Opportunities</h1>
        <p class="text-xs text-slate-500">Apply for verified transition grants, asset financing, jobs, and commercial transport contracts.</p>
    </div>

    <!-- My Applications -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-[#002040] font-heading">My Submitted Applications</h2>

        <div class="space-y-3">
            @forelse ($myApplications as $app)
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-[#108010] bg-[#108010]/10 px-2 py-0.5 rounded-full">{{ $app->opportunity->category->name }}</span>
                        <h3 class="font-bold text-sm text-[#002040] mt-1">{{ $app->opportunity->title }}</h3>
                        <p class="text-xs font-mono text-slate-500">{{ $app->application_number }} • Submitted {{ $app->created_at->format('d M Y') }}</p>
                        @if ($app->admin_feedback)
                            <p class="text-xs text-emerald-800 bg-[#108010]/10 p-2 rounded-lg border border-[#108010]/20 mt-2"><strong>Admin Feedback:</strong> {{ $app->admin_feedback }}</p>
                        @endif
                    </div>
                    <span class="text-xs font-bold px-3 py-1 rounded-full uppercase {{ $app->status === 'accepted' ? 'bg-[#108010]/10 text-[#108010]' : ($app->status === 'rejected' ? 'bg-rose-100 text-rose-800' : 'bg-amber-100 text-amber-800') }}">
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
        <h2 class="text-lg font-bold text-[#002040] font-heading">Available Opportunities</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($opportunities as $opp)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-[#108010] transition" x-data="{ applyOpen: false }">
                    <div>
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] uppercase font-bold text-[#108010] bg-[#108010]/10 px-2.5 py-0.5 rounded-full">{{ $opp->category->name }}</span>
                            @if ($opp->deadline)
                                <span class="text-xs text-slate-500">Deadline: {{ $opp->deadline->format('d M Y') }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-base text-[#002040] mb-1">{{ $opp->title }}</h3>
                        <p class="text-xs text-slate-500 mb-4">{{ $opp->provider_name }} • {{ $opp->location }}</p>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-2">{{ $opp->summary }}</p>
                    </div>

                    <div>
                        @if ($opp->application_type === 'external')
                            <a href="{{ $opp->external_url }}" target="_blank" class="w-full text-center bg-[#002040] hover:bg-slate-800 text-white text-xs font-bold py-2.5 px-4 rounded-xl transition block">
                                Apply via Official Channel &rarr;
                            </a>
                        @else
                            <button type="button" @click="applyOpen = !applyOpen" class="w-full bg-[#108010] hover:bg-[#0c660c] text-white text-xs font-bold py-2.5 px-4 rounded-xl transition cursor-pointer">
                                Apply Now (Internal)
                            </button>

                            <!-- Inline Application Modal / Dropdown -->
                            <div x-show="applyOpen" x-transition class="mt-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-3">
                                <form action="{{ route('portal.opportunities.apply', $opp->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Cover Note / Motivation</label>
                                        <textarea name="statement_of_interest" rows="3" required placeholder="Explain why you qualify for this opportunity..." class="w-full bg-white border border-slate-300 rounded-xl p-2.5 text-xs focus:ring-2 focus:ring-[#108010]/20 focus:border-[#108010] outline-none"></textarea>
                                    </div>
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="applyOpen = false" class="px-3 py-1.5 text-xs text-slate-500">Cancel</button>
                                        <button type="submit" class="bg-[#108010] hover:bg-[#0c660c] text-white font-bold px-4 py-1.5 rounded-xl text-xs transition cursor-pointer">Submit Application</button>
                                    </div>
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
