@extends('layouts.admin')

@section('title', 'Manage Homepage Sliders — TEVDA Admin')
@section('page_title', 'Homepage Sliders')

@section('content')
<div class="space-y-6">

    <!-- Header Actions & Metrics -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </span>
                <h2 class="text-xl font-black text-slate-900 font-heading tracking-tight">Hero Slider Management</h2>
            </div>
            <p class="text-xs text-slate-500 mt-1">
                Upload, order, activate, or replace high-resolution hero images displayed on the public homepage.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-2 border border-slate-200">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>View Live Site</span>
            </a>
            <a href="{{ route('admin.cms.sliders.create') }}" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl transition shadow-md shadow-emerald-600/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Upload New Slide</span>
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Slides</span>
                <span class="text-2xl font-black text-slate-900 font-heading mt-0.5 block">{{ $totalCount }}</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block">Active (Live on Home)</span>
                <span class="text-2xl font-black text-emerald-600 font-heading mt-0.5 block">{{ $activeCount }}</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Hidden / Inactive</span>
                <span class="text-2xl font-black text-slate-500 font-heading mt-0.5 block">{{ $totalCount - $activeCount }}</span>
            </div>
            <div class="w-11 h-11 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
            </div>
        </div>
    </div>

    <!-- Sliders Cards Grid -->
    @if($sliders->isEmpty())
        <div class="bg-white p-12 text-center rounded-3xl border border-slate-200 shadow-xs">
            <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 font-heading">No Slides Configured Yet</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
                Upload your first high-resolution photo to showcase commercial electric vehicles, training programs, or events on the homepage.
            </p>
            <a href="{{ route('admin.cms.sliders.create') }}" class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                <span>Upload First Slide</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sliders as $slider)
                <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden flex flex-col group transition hover:shadow-lg hover:border-emerald-300">
                    
                    <!-- Slide Image Preview -->
                    <div class="relative h-52 sm:h-56 bg-slate-950 overflow-hidden">
                        <img src="{{ $slider->image_url }}" 
                             alt="{{ $slider->title ?? 'Slide #'.$slider->id }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Order & Status Badges -->
                        <div class="absolute top-3 left-3 flex items-center gap-1.5">
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-black bg-slate-950/80 text-white backdrop-blur-md border border-white/20 shadow-md">
                                Order: #{{ $slider->order_number }}
                            </span>
                        </div>

                        <div class="absolute top-3 right-3">
                            @if($slider->is_active)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500 text-white shadow-md">
                                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Active / Live
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-slate-800 text-slate-300 shadow-md">
                                    Hidden
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Slide Info -->
                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <h4 class="text-sm font-black text-slate-900 font-heading line-clamp-1">
                                {{ $slider->title ?: 'Untitled Slide #'.$slider->id }}
                            </h4>
                            @if($slider->subtitle)
                                <p class="text-xs text-slate-500 mt-1 line-clamp-2">
                                    {{ $slider->subtitle }}
                                </p>
                            @else
                                <p class="text-xs text-slate-400 mt-1 italic">
                                    Clean photo slide (no caption)
                                </p>
                            @endif
                        </div>

                        <!-- Card Action Buttons -->
                        <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.cms.sliders.edit', $slider->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    <span>Edit</span>
                                </a>

                                <form method="POST" action="{{ route('admin.cms.sliders.toggle', $slider->id) }}">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 {{ $slider->is_active ? 'bg-amber-50 hover:bg-amber-100 text-amber-700' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700' }} font-bold text-xs rounded-xl transition cursor-pointer" title="{{ $slider->is_active ? 'Hide from homepage' : 'Show on homepage' }}">
                                        {{ $slider->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>
                            </div>

                            <form method="POST" action="{{ route('admin.cms.sliders.destroy', $slider->id) }}" onsubmit="return confirm('Are you sure you want to delete this slider photo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition cursor-pointer" title="Delete slide">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Tips & Optimal Dimensions Info Card -->
    <div class="bg-gradient-to-r from-slate-900 to-slate-950 text-white p-6 rounded-3xl border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white">Recommended Slider Photo Specifications</h4>
                <p class="text-xs text-slate-300 mt-0.5">
                    For optimal clarity across desktop and mobile screens, upload panoramic photos with a resolution of <strong class="text-emerald-400">1920 &times; 800 px</strong> or <strong class="text-emerald-400">1600 &times; 700 px</strong> (JPG, PNG, WebP up to 10MB).
                </p>
            </div>
        </div>
        <a href="{{ route('admin.cms.sliders.create') }}" class="px-4 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-xs rounded-xl whitespace-nowrap transition">
            + Add Slide
        </a>
    </div>

</div>
@endsection
