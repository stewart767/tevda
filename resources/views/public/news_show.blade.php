@extends('layouts.app')

@section('title', $article->title . ' — TEVDA News')

@section('content')
<div class="relative bg-[#002040] text-white py-16 lg:py-20 overflow-hidden">
    <!-- Subtle Brand Background Gradients -->
    <div class="absolute -right-24 -top-24 w-96 h-96 bg-[#108010]/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -left-24 -bottom-24 w-96 h-96 bg-[#E5B51B]/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex items-center gap-2 mb-4">
            <span class="text-xs font-extrabold uppercase tracking-widest text-[#E5B51B] bg-white/10 px-3 py-1 rounded-lg border border-[#E5B51B]/30">
                {{ ucwords(str_replace('_', ' ', $article->category)) }}
            </span>
            <span class="text-xs text-slate-300">{{ $article->publication_date->format('d F Y') }}</span>
        </div>
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black font-heading leading-tight text-white tracking-tight">{{ $article->title }}</h1>
        <p class="text-slate-300 text-xs sm:text-sm mt-3">Published by {{ $article->author_name }}</p>
    </div>
</div>

<div class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        @if ($article->featured_image_url)
            <div class="rounded-3xl overflow-hidden shadow-xl border border-slate-200 h-72 sm:h-96 w-full">
                <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
            </div>
        @endif

        <div class="p-8 bg-[#108010]/5 rounded-3xl border border-[#108010]/20 text-slate-800 text-sm sm:text-base font-medium leading-relaxed">
            {{ $article->summary }}
        </div>

        <div class="prose prose-slate max-w-none text-slate-700 text-sm sm:text-base leading-relaxed space-y-4">
            {!! nl2br(e($article->content)) !!}
        </div>

        <div class="pt-8 border-t border-slate-200 flex justify-between items-center">
            <a href="{{ route('news') }}" class="text-xs sm:text-sm font-bold text-[#108010] hover:text-[#002040] flex items-center gap-1.5 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Back to News & Media</span>
            </a>
        </div>

    </div>
</div>
@endsection
