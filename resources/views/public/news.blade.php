@extends('layouts.app')

@section('title', 'News, Events & Media Statements — TEVDA')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center sm:text-left">
        <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3.5 py-1.5 rounded-full border border-emerald-800/80">Media & Secretariat Releases</span>
        <h1 class="text-3xl sm:text-5xl font-black font-heading mt-3 mb-3 text-white">News & Events</h1>
        <p class="text-slate-300 text-sm sm:text-base max-w-2xl leading-relaxed">Official media statements, driver workshops, regulatory notices, and policy statements from TEVDA Secretariat.</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Category Filter -->
        <div class="flex flex-wrap gap-2 pb-4 border-b border-slate-200">
            <a href="{{ route('news') }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ !request('category') ? 'bg-slate-950 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                All Updates
            </a>
            @foreach (['announcements', 'training', 'opportunities', 'events', 'articles', 'media_statements'] as $cat)
                <a href="{{ route('news', ['category' => $cat]) }}" class="px-4 py-2 rounded-xl text-xs font-bold {{ request('category') === $cat ? 'bg-slate-950 text-white shadow-md' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition">
                    {{ ucwords(str_replace('_', ' ', $cat)) }}
                </a>
            @endforeach
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($news as $item)
                <div class="glass-card rounded-3xl overflow-hidden border border-slate-200/80 shadow-xs flex flex-col justify-between group">
                    <div>
                        <!-- Thumbnail Image -->
                        <div class="h-48 w-full bg-slate-900 relative overflow-hidden flex items-center justify-center">
                            @if ($item->featured_image_url)
                                <img src="{{ $item->featured_image_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <div class="hidden absolute inset-0 bg-gradient-to-br from-emerald-900 to-slate-950 flex items-center justify-center p-6 text-center">
                                    <span class="text-white font-black text-lg font-heading">{{ $item->title }}</span>
                                </div>
                            @else
                                <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 flex items-center justify-center p-6 text-center">
                                    <div class="space-y-1.5">
                                        <div class="w-10 h-10 mx-auto rounded-xl bg-emerald-600/30 border border-emerald-400/40 text-emerald-400 flex items-center justify-center font-bold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                        </div>
                                        <span class="text-white font-bold text-xs font-heading block line-clamp-2">{{ $item->title }}</span>
                                    </div>
                                </div>
                            @endif

                            <div class="absolute top-3 left-3">
                                <span class="text-[10px] uppercase font-extrabold text-emerald-300 bg-slate-950/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-emerald-800/60">
                                    {{ ucwords(str_replace('_', ' ', $item->category)) }}
                                </span>
                            </div>
                        </div>

                        <div class="p-6 space-y-2.5">
                            <span class="text-[11px] text-slate-400 font-semibold block">{{ $item->publication_date->format('d F Y') }}</span>
                            
                            <h3 class="font-black text-lg text-slate-900 font-heading leading-snug">
                                <a href="{{ route('news.show', $item->slug) }}" class="hover:text-emerald-700 transition">{{ $item->title }}</a>
                            </h3>

                            <p class="text-xs text-slate-600 leading-relaxed line-clamp-3">{{ $item->summary }}</p>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2 flex justify-between items-center border-t border-slate-100 text-xs mt-2">
                        <span class="text-[11px] text-slate-400 font-medium">By {{ $item->author_name }}</span>
                        <a href="{{ route('news.show', $item->slug) }}" class="font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1">
                            <span>Read Article</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-slate-500 text-xs sm:text-sm bg-slate-50 rounded-3xl border border-dashed border-slate-300">
                    No articles currently published in this category.
                </div>
            @endforelse
        </div>

        <div class="pt-6">
            {{ $news->links() }}
        </div>

    </div>
</div>
@endsection
