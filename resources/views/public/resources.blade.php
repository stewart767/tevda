@extends('layouts.app')

@section('title', 'Resource Center & Publications — TEVDA')

@section('content')
<div class="bg-slate-900 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center sm:text-left">
        <span class="text-xs font-bold uppercase tracking-widest text-emerald-400 bg-emerald-950 px-3 py-1 rounded-md border border-emerald-800">Knowledge Hub</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3">Resource Center & Documents</h1>
        <p class="text-slate-300 text-base max-w-2xl">Access official association constitutions, governance guidelines, driver manuals, policy briefs, and membership forms.</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
            <a href="{{ route('resources') }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ !request('category') ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                All Documents
            </a>
            @foreach (['constitution_and_governance', 'membership', 'training', 'projects', 'policies', 'reports', 'forms'] as $cat)
                <a href="{{ route('resources', ['category' => $cat]) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('category') === $cat ? 'bg-slate-900 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                    {{ ucwords(str_replace('_', ' ', $cat)) }}
                </a>
            @endforeach
        </div>

        <!-- Resources Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($resources as $res)
                <div class="bg-slate-50 p-6 rounded-3xl border border-slate-200 shadow-xs flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-[10px] uppercase font-bold text-emerald-700 bg-emerald-100 px-2.5 py-0.5 rounded-full">
                                {{ ucwords(str_replace('_', ' ', $res->category)) }}
                            </span>
                            <span class="text-[10px] text-slate-500">v{{ $res->version }}</span>
                        </div>

                        <h3 class="font-bold text-base text-slate-900 font-heading mb-2">{{ $res->title }}</h3>
                        <p class="text-xs text-slate-500 mb-4">Authority: {{ $res->approving_authority }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                        <span class="text-[11px] text-slate-400 uppercase font-semibold">{{ $res->file_type }} • {{ $res->downloads_count }} downloads</span>
                        <a href="{{ route('resources.download', $res->slug) }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition shadow-xs">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Download</span>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500 text-sm">
                    No documents currently listed in this category.
                </div>
            @endforelse
        </div>

        <div class="pt-6">
            {{ $resources->links() }}
        </div>

    </div>
</div>
@endsection
