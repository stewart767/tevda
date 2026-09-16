@extends('layouts.portal')

@section('title', 'Training & Certification — TEVDA Member Portal')

@section('content')
<div class="space-y-8">
    
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 font-heading">Driver Training & Certification</h1>
            <p class="text-xs text-slate-500">Enrol in verified training sessions, attend classes, pass assessments, and earn verifiable certificates.</p>
        </div>
    </div>

    <!-- Active Enrolments -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-4">
        <h2 class="text-base font-bold text-slate-900 font-heading">My Training Enrolments</h2>

        <div class="space-y-4">
            @forelse ($myEnrolments as $enrolment)
                <div class="p-5 bg-slate-50 rounded-2xl border border-slate-200 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-1">
                        <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">{{ $enrolment->session->session_code }}</span>
                        <h3 class="font-bold text-sm sm:text-base text-slate-900">{{ $enrolment->session->course->title }}</h3>
                        <p class="text-xs text-slate-500">Date: {{ $enrolment->session->start_date->format('d M Y') }} • Venue: {{ $enrolment->session->location }} ({{ $enrolment->session->venue ?? 'Main Center' }})</p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if ($enrolment->status === 'completed' && $enrolment->result?->certificate)
                            <a href="{{ route('portal.certificate.download', $enrolment->result->certificate->id) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Download Certificate (Score: {{ $enrolment->result->score }}%)</span>
                            </a>
                        @elseif ($enrolment->status === 'failed')
                            <span class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-full border border-rose-200">Re-take Required (Score: {{ $enrolment->result?->score ?? 'N/A' }}%)</span>
                        @else
                            <span class="text-xs font-bold text-slate-700 bg-slate-200 px-3 py-1 rounded-full">{{ strtoupper($enrolment->status) }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-xs text-slate-500 py-4 text-center">You have no active enrolments. Browse the programmes below to register.</p>
            @endforelse
        </div>
    </div>

    <!-- Available Training Sessions to Enrol -->
    <div class="space-y-6">
        <h2 class="text-lg font-bold text-slate-900 font-heading">Upcoming Training Sessions Open for Enrolment</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($programmes as $prog)
                @foreach ($prog->courses as $course)
                    @foreach ($course->sessions as $session)
                        <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm flex flex-col justify-between hover:border-emerald-500 transition">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">{{ $prog->code }}</span>
                                    <span class="text-xs text-slate-500 font-mono">{{ $session->session_code }}</span>
                                </div>
                                <h3 class="font-bold text-base text-slate-900 mb-1">{{ $course->title }}</h3>
                                <p class="text-xs text-slate-500 leading-relaxed mb-4 line-clamp-2">{{ $course->description }}</p>

                                <div class="space-y-1 text-xs text-slate-600 mb-4 p-3 bg-slate-50 rounded-xl">
                                    <p><strong>Date:</strong> {{ $session->start_date->format('d M Y') }}</p>
                                    <p><strong>Location:</strong> {{ $session->location }}</p>
                                    <p><strong>Capacity:</strong> {{ $session->available_seats }} seats available</p>
                                </div>
                            </div>

                            <form action="{{ route('portal.training.enroll', $session->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold py-2.5 rounded-xl transition shadow-xs">
                                    Register for this Session
                                </button>
                            </form>
                        </div>
                    @endforeach
                @endforeach
            @endforeach
        </div>
    </div>

</div>
@endsection
