@extends('layouts.app')

@section('title', 'Grants, Financing, Fleet Jobs & Opportunities — TEVDA')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest text-[#E5B51B] bg-white/10 backdrop-blur-md px-3.5 py-1.5 rounded-full border border-[#E5B51B]/30">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Growth & Opportunities</span>
        </span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white tracking-tight">Opportunities Directory</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Verified clean energy transition grants, affordable asset financing, corporate fleet employment openings, and procurement contracts for licensed drivers.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
            <a href="{{ route('opportunities') }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ !request('category') ? 'bg-[#002040] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                All Categories
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('opportunities', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('category') === $cat->slug ? 'bg-[#002040] text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Opportunities Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($opportunities as $opp)
                <div class="bg-white rounded-3xl p-6 border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group">
                    <div>
                        <div class="flex justify-between items-start gap-2 mb-4">
                            <span class="text-[10px] uppercase font-extrabold text-[#108010] bg-[#108010]/10 px-2.5 py-1 rounded-full border border-[#108010]/20">
                                {{ $opp->category->name }}
                            </span>
                            @if ($opp->deadline)
                                <span class="text-[10px] text-slate-500 font-semibold bg-slate-100 px-2 py-0.5 rounded-md">
                                    Deadline: {{ $opp->deadline->format('d M Y') }}
                                </span>
                            @endif
                        </div>

                        <div class="flex items-start gap-3.5 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 text-[#002040] flex items-center justify-center font-black text-sm shrink-0 border border-slate-200 overflow-hidden">
                                @if ($opp->provider_logo_url)
                                    <img src="{{ $opp->provider_logo_url }}" alt="{{ $opp->provider_name }}" class="w-full h-full object-contain p-1" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                    <span class="hidden">{{ strtoupper(substr($opp->provider_name, 0, 2)) }}</span>
                                @else
                                    <span>{{ strtoupper(substr($opp->provider_name, 0, 2)) }}</span>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-black text-lg text-[#002040] font-heading leading-tight">
                                    <a href="{{ route('opportunities.show', $opp->slug) }}" class="hover:text-[#108010] transition">{{ $opp->title }}</a>
                                </h3>
                                <p class="text-xs text-slate-500 mt-0.5 font-medium">{{ $opp->provider_name }}</p>
                            </div>
                        </div>

                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">{{ $opp->summary }}</p>

                        <div class="space-y-1 text-xs text-slate-500 pt-3 border-t border-slate-100">
                            <p><strong>Location:</strong> {{ $opp->location }}</p>
                            <p><strong>Application Mode:</strong> {{ $opp->application_type === 'internal' ? 'Member Portal Direct Intake' : 'Official External Channel' }}</p>
                        </div>
                    </div>

                    <div class="pt-4 mt-4 border-t border-slate-100 flex justify-between items-center">
                        <a href="{{ route('opportunities.show', $opp->slug) }}" class="text-xs font-bold text-[#108010] hover:text-[#002040] flex items-center gap-1 transition">
                            <span>View Requirements</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500 text-xs sm:text-sm bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                    No active opportunities listed under this category at present.
                </div>
            @endforelse
        </div>

        <div class="pt-6">
            {{ $opportunities->links() }}
        </div>

    </div>
</div>
@endsection
