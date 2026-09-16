@extends('layouts.app')

@section('title', 'Driver Training Programmes & Certification — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Capacity Building</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Driver Training Programmes</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Standardized national curricula empowering commercial EV drivers with high-voltage battery mastery, defensive road safety, and micro-fleet enterprise skills.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
        
        <!-- Programmes List -->
        <div class="space-y-12">
            @foreach ($programmes as $prog)
                <div class="glass-card rounded-3xl p-8 border border-slate-200/80 shadow-xs">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-6 pb-6 border-b border-slate-200">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-extrabold uppercase text-emerald-800 bg-emerald-100 px-3 py-1 rounded-full border border-emerald-200/60">Programme {{ $prog->code }}</span>
                            </div>
                            <h2 class="text-2xl font-black text-slate-900 font-heading">{{ $prog->title }}</h2>
                            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-3xl leading-relaxed">{{ $prog->description }}</p>
                        </div>
                        @if ($prog->objective)
                            <div class="p-4 bg-emerald-50/80 rounded-2xl border border-emerald-200/60 text-xs text-slate-700 max-w-sm">
                                <strong class="text-emerald-950 block mb-1 font-bold">Curriculum Objective:</strong>
                                {{ $prog->objective }}
                            </div>
                        @endif
                    </div>

                    <!-- Courses & Modules -->
                    <div class="space-y-6">
                        @foreach ($prog->courses as $course)
                            <div class="bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4">
                                    <h3 class="text-base sm:text-lg font-black text-slate-900 font-heading">{{ $course->title }}</h3>
                                    <div class="flex items-center gap-3 text-xs text-slate-500 font-semibold">
                                        <span class="bg-slate-100 px-2.5 py-1 rounded-lg">Duration: <strong>{{ $course->duration_hours }} Hours</strong></span>
                                        <span class="bg-emerald-50 text-emerald-800 px-2.5 py-1 rounded-lg border border-emerald-200/60">Pass Mark: <strong>{{ $course->pass_mark_percentage }}%</strong></span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 mb-4">{{ $course->description }}</p>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    @foreach ($course->modules as $mod)
                                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200/60 flex flex-col justify-between">
                                            <div>
                                                <span class="text-[10px] font-extrabold text-emerald-700 uppercase block mb-1">Module {{ $mod->order_number }} • {{ $mod->duration_hours }}h</span>
                                                <h4 class="text-xs font-bold text-slate-900 mb-1 leading-snug">{{ $mod->title }}</h4>
                                                <p class="text-[11px] text-slate-500 leading-relaxed">{{ $mod->description }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Call to Action -->
        <div class="bg-slate-950 rounded-3xl p-8 sm:p-12 text-white text-center shadow-2xl border border-slate-800">
            <h3 class="text-2xl sm:text-3xl font-black font-heading mb-3 text-white">Enrol in an Upcoming Training Session</h3>
            <p class="text-slate-300 text-xs sm:text-sm max-w-2xl mx-auto mb-6 leading-relaxed">
                Active TEVDA members can register for certified training sessions directly through the Member Portal and receive verifiable digital completion certificates.
            </p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black px-8 py-4 rounded-2xl text-xs sm:text-sm transition shadow-xl">
                    Register as Member to Enrol
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
