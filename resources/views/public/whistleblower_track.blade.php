@extends('layouts.app')

@section('title', 'Track Incident Report Status — TEVDA')

@section('content')
<div class="bg-slate-900 text-white py-16">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3 py-1 rounded-md border border-emerald-800">Confidential Tracking</span>
        <h1 class="text-3xl sm:text-4xl font-black font-heading mt-3 mb-2">Track Report Ticket Status</h1>
        <p class="text-slate-300 text-sm">Enter your reference ticket ID to check the investigation and resolution progress of your confidential report.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Search Form -->
        <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200">
            <form action="{{ route('whistleblower.track') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <input type="text" name="ticket" value="{{ $ticket }}" placeholder="e.g. TEVDA-CMP-2026-0001" required class="flex-1 bg-white border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 font-mono">
                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-6 py-3 rounded-xl text-sm transition">
                    Check Status
                </button>
            </form>
        </div>

        <!-- Result -->
        @if ($ticket)
            @if ($complaint)
                <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-lg space-y-6">
                    <div class="flex justify-between items-start border-b border-slate-100 pb-4">
                        <div>
                            <span class="text-xs text-slate-400 block">Report Ticket Reference:</span>
                            <span class="text-lg font-bold font-mono text-slate-900">{{ $complaint->complaint_number }}</span>
                        </div>
                        <span class="text-xs font-bold px-3 py-1 rounded-full uppercase {{ in_array($complaint->status, ['resolved', 'closed']) ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                            {{ ucwords(str_replace('_', ' ', $complaint->status)) }}
                        </span>
                    </div>

                    <div class="space-y-3 text-xs sm:text-sm text-slate-700">
                        <p><strong>Category:</strong> {{ ucwords(str_replace('_', ' ', $complaint->category)) }}</p>
                        <p><strong>Date Submitted:</strong> {{ $complaint->created_at->format('d F Y, H:i') }}</p>
                        @if ($complaint->resolution_summary)
                            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-emerald-900 mt-4">
                                <strong class="block mb-1 font-bold">Official Resolution Note:</strong>
                                {{ $complaint->resolution_summary }}
                            </div>
                        @else
                            <p class="text-slate-500 italic mt-2">This report is actively being reviewed by the designated TEVDA Governance Officer.</p>
                        @endif
                    </div>
                </div>
            @else
                <div class="p-6 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-sm text-center">
                    No incident report found matching ticket reference "<strong>{{ $ticket }}</strong>". Please double check the ID.
                </div>
            @endif
        @endif

    </div>
</div>
@endsection
