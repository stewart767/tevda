@extends('layouts.app')

@section('title', $article->title . ' — TEVDA News')

@section('content')
<div class="relative hero-pattern text-white py-16 lg:py-20 overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-emerald-300 bg-emerald-950/80 px-3 py-1 rounded-lg border border-emerald-800/80">
                {{ ucwords(str_replace('_', ' ', $article->category)) }}
            </span>
            <span class="text-xs text-slate-300">{{ $article->publication_date->format('d F Y') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading leading-tight text-white">{{ $article->title }}</h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-3">Published by {{ $article->author_name }}</p>
    </div>
</div>

<div class="py-16 bg-white subtle-grid-pattern">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        @if ($article->featured_image_url)
            <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200 h-72 sm:h-96 w-full">
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8 bg-emerald-50/70 rounded-3xl border border-emerald-200/80 text-slate-800 text-sm sm:text-base font-medium leading-relaxed">
            {{ $article->summary }}
        </div>

        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="pt-8 border-t border-slate-200 flex justify-between items-center">
            <a href="{{ route('news') }}" class="text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-800 flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to News & Media</span>
            </a>
        </div>

    </div>
</div>
@endsection
