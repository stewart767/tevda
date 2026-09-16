@extends('layouts.app')

@section('title', 'Strategic Electric Mobility Projects — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Clean Transport Initiatives</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">Electric Mobility Projects</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Explore strategic initiatives, charging infrastructure pilots, and commercial EV deployment programmes across Tanzania.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
            <a href="{{ route('projects') }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ !request('category') ? 'bg-slate-950 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                All Projects
            </a>
            @foreach ($categories as $cat)
                <a href="{{ route('projects', ['category' => $cat->slug]) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('category') === $cat->slug ? 'bg-slate-950 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($projects as $project)
                <div class="glass-card rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs flex flex-col justify-between group">
                    <div>
                        <!-- Project Image / Banner -->
                        <div class="h-48 w-full bg-slate-900 relative overflow-hidden flex items-center justify-center">
                            @if ($project->featured_image_url)
                                <img src="{{ $project->featured_image_url }}" alt="{{ $project->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden absolute inset-0 bg-gradient-to-br from-emerald-900 to-slate-950 flex items-center justify-center p-6 text-center">
                                    <span class="text-white font-black text-xl font-heading">{{ $project->title }}</span>
                                </div>
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 flex items-center justify-center p-6 text-center">
                                    <div class="space-y-2">
                                        <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-600/30 border border-emerald-400/40 text-emerald-400 flex items-center justify-center font-bold">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                        <span class="text-white font-black text-sm font-heading block line-clamp-2">{{ $project->title }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                <span class="text-[10px] uppercase font-extrabold text-white bg-slate-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/20">
                                    {{ $project->category->name }}
                                </span>
                            </div>

                            <div class="absolute top-3 right-3">
                                @if ($project->project_status === 'open_for_applications')
                                    <span class="text-[10px] font-extrabold bg-emerald-500 text-slate-950 px-2.5 py-1 rounded-lg shadow-md">Open for Applications</span>
                                @elseif ($project->funding_status === 'proposal_under_development')
                                    <span class="text-[10px] font-extrabold bg-amber-500 text-slate-950 px-2.5 py-1 rounded-lg">Proposal Phase</span>
                                @else
                                    <span class="text-[10px] font-bold bg-slate-900/80 text-slate-200 px-2.5 py-1 rounded-lg">{{ ucwords(str_replace('_', ' ', $project->project_status)) }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="p-6 space-y-3">
                            <h3 class="font-black text-xl text-slate-900 font-heading leading-snug">
                                <a href="{{ route('projects.show', $project->slug) }}" class="hover:text-emerald-700 transition">{{ $project->title }}</a>
                            </h3>

                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">{{ $project->summary }}</p>

                            <div class="space-y-1.5 text-xs text-slate-500 pt-3 border-t border-slate-100">
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <span><strong>Location:</strong> {{ $project->location }}</span>
                                </p>
                                <p class="flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    <span><strong>Target Beneficiaries:</strong> {{ $project->target_beneficiaries_count }} Drivers</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2 flex justify-between items-center border-t border-slate-100 mt-2">
                        <a href="{{ route('projects.show', $project->slug) }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                            <span>Read Full Project Specs</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500 text-xs sm:text-sm bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                    No strategic projects found under this category.
                </div>
            @endforelse
        </div>

        <div class="pt-6">
            {{ $projects->links() }}
        </div>

    </div>
</div>
@endsection
