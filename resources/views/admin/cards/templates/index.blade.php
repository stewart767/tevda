@extends('layouts.admin')

@section('title', 'ID Card Templates & Layout Studio')

@section('content')
<div class="space-y-6">

    {{-- Top Header & Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div>
            <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                <a href="{{ route('admin.cards.index') }}" class="hover:text-emerald-600 transition">ID Cards</a>
                <span>/</span>
                <span class="text-slate-900 font-semibold">Templates & Backgrounds</span>
            </div>
            <h1 class="text-xl font-black font-heading text-slate-900 tracking-tight">ID Card Templates Studio</h1>
            <p class="text-xs text-slate-500 mt-0.5">Upload custom background artwork and customize field coordinates with live auto-detection.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <a href="{{ route('admin.cards.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                <i class="fa-solid fa-arrow-left text-slate-400"></i>
                <span>Issued Cards</span>
            </a>

            <a href="{{ route('admin.cards.templates.create') }}" class="inline-flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl shadow-xs transition">
                <i class="fa-solid fa-cloud-arrow-up"></i>
                <span>Upload New Template</span>
            </a>
        </div>
    </div>

    @include('partials.alerts')

    {{-- Summary Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-id-card-clip"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Templates</span>
                <div class="text-2xl font-black text-slate-900 font-heading">{{ $totalTemplates }}</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Active Templates</span>
                <div class="text-2xl font-black text-slate-900 font-heading">{{ $activeTemplates }}</div>
            </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 shadow-xs flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-star"></i>
            </div>
            <div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-400">System Default</span>
                <div class="text-sm font-bold text-slate-900 truncate">
                    {{ $defaultTemplate ? $defaultTemplate->name : 'Built-in CR80 Standard' }}
                </div>
            </div>
        </div>
    </div>

    {{-- Templates Grid --}}
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-sm font-bold font-heading text-slate-900">Custom Card Templates</h2>
            <span class="text-xs text-slate-400">{{ $templates->count() }} template(s) registered</span>
        </div>

        @if($templates->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-xs space-y-4">
                <div class="w-16 h-16 rounded-3xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-xs">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900 font-heading">No Custom ID Card Templates Uploaded Yet</h3>
                    <p class="text-xs text-slate-500 max-w-md mx-auto mt-1">
                        Currently, the system uses the high-security built-in CR80 design. Upload custom background artwork (front & back) to customize layouts, branding, and placement.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('admin.cards.templates.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-5 py-2.5 rounded-xl shadow-xs transition">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        <span>Upload First Template</span>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($templates as $tpl)
                    <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs hover:shadow-md transition flex flex-col justify-between">
                        
                        {{-- Card Thumbnail Header --}}
                        <div class="p-5 pb-3 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $tpl->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ $tpl->is_active ? 'Active' : 'Inactive' }}
                                </span>

                                @if($tpl->is_default)
                                    <span class="text-[10px] font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200 flex items-center gap-1">
                                        <i class="fa-solid fa-star text-amber-500 text-[9px]"></i>
                                        <span>Default Template</span>
                                    </span>
                                @endif
                            </div>

                            {{-- Visual Thumbnail --}}
                            <div class="relative w-full rounded-2xl overflow-hidden bg-slate-900 border border-slate-200" style="aspect-ratio: 85.6 / 53.98;">
                                @if($tpl->front_background_image_path)
                                    <img src="{{ asset('storage/' . $tpl->front_background_image_path) }}" alt="{{ $tpl->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-emerald-950 via-teal-900 to-slate-950 p-4 flex flex-col justify-between text-white border border-emerald-500/30">
                                        <div class="flex justify-between items-center text-[10px] font-bold text-emerald-400">
                                            <span>TEVDA</span>
                                            <span>CR80</span>
                                        </div>
                                        <div class="text-center text-xs font-black tracking-wide text-white">
                                            {{ $tpl->name }}
                                        </div>
                                        <div class="text-[8px] text-slate-400 text-center uppercase tracking-wider font-mono">
                                            {{ $tpl->code }}
                                        </div>
                                    </div>
                                @endif
                                
                                <span class="absolute bottom-2 right-2 text-[9px] font-bold bg-black/70 text-white px-2 py-0.5 rounded-md backdrop-blur-xs">
                                    {{ strtoupper($tpl->card_type) }}
                                </span>
                            </div>

                            <div>
                                <h3 class="font-bold text-slate-900 text-sm font-heading truncate">{{ $tpl->name }}</h3>
                                <p class="text-[11px] text-slate-400 font-mono">Code: {{ $tpl->code }}</p>
                            </div>
                        </div>

                        {{-- Card Meta & Actions --}}
                        <div class="p-5 pt-3 border-t border-slate-100 bg-slate-50/50 space-y-3">
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span>Used by: <strong class="text-slate-800">{{ $tpl->cards_count }}</strong> cards</span>
                                <span class="text-[10px] text-slate-400">{{ $tpl->updated_at->format('M d, Y') }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-2 pt-1">
                                <a href="{{ route('admin.cards.templates.edit', $tpl->id) }}" class="inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-xs transition">
                                    <i class="fa-solid fa-sliders"></i>
                                    <span>Open Studio</span>
                                </a>

                                <a href="{{ route('admin.cards.templates.preview_pdf', $tpl->id) }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 py-2 px-3 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 font-bold text-xs transition">
                                    <i class="fa-solid fa-file-pdf text-rose-500"></i>
                                    <span>Preview PDF</span>
                                </a>
                            </div>

                            <div class="flex items-center justify-between pt-1 text-xs">
                                @if(!$tpl->is_default)
                                    <form action="{{ route('admin.cards.templates.set_default', $tpl->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 transition">
                                            <i class="fa-solid fa-star text-amber-400 mr-0.5"></i> Set as Default
                                        </button>
                                    </form>
                                @else
                                    <span class="text-[11px] font-bold text-emerald-700 flex items-center gap-1">
                                        <i class="fa-solid fa-check-circle text-emerald-500"></i> Active Default
                                    </span>
                                @endif

                                <form action="{{ route('admin.cards.templates.destroy', $tpl->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete template {{ $tpl->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[11px] font-bold text-rose-600 hover:text-rose-700 transition">
                                        <i class="fa-solid fa-trash-can mr-0.5"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
