@extends('layouts.admin')

@section('title', 'Certificate Templates')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.certificates.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 font-heading">Certificate Templates & Layouts</h1>
                <p class="text-xs text-slate-500">Manage graphic templates, dynamic text coordinates, and QR code placement for PDF certificates.</p>
            </div>
        </div>

        <a href="{{ route('admin.certificates.templates.create') }}" class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <span>Upload New Template</span>
        </a>
    </div>

    @include('partials.alerts')

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($templates as $tmpl)
            <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs hover:shadow-md transition flex flex-col justify-between overflow-hidden group">
                <div>
                    {{-- Thumbnail / Template Graphic Preview --}}
                    <div class="relative w-full h-44 bg-slate-100 border-b border-slate-100 flex items-center justify-center overflow-hidden">
                        @if($tmpl->background_image_path)
                            <img src="{{ $tmpl->getBackgroundImageUrl() }}" alt="{{ $tmpl->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            <span class="absolute bottom-3 left-3 text-[10px] font-bold text-white bg-slate-900/80 backdrop-blur-xs px-2.5 py-1 rounded-lg">
                                <i class="fa-solid fa-image mr-1 text-emerald-400"></i> Custom Artwork
                            </span>
                        @else
                            <div class="text-center p-6 space-y-2">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shadow-xs">
                                    <i class="fa-solid fa-certificate"></i>
                                </div>
                                <span class="text-xs font-bold text-slate-600">Standard Vector Border</span>
                            </div>
                        @endif

                        <div class="absolute top-3 right-3 flex items-center gap-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-700 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-lg shadow-xs">
                                <i class="fa-solid fa-repeat text-slate-400 mr-1"></i> {{ ucfirst($tmpl->orientation) }}
                            </span>
                        </div>
                    </div>

                    {{-- Body Details --}}
                    <div class="p-5 space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 border border-emerald-200/60 px-2.5 py-0.5 rounded-full">
                                {{ strtoupper(str_replace('_', ' ', $tmpl->certificate_type)) }}
                            </span>
                            <span class="text-xs font-bold text-slate-500">
                                <i class="fa-solid fa-award text-amber-500 mr-1"></i>{{ $tmpl->certificates_count }} Issued
                            </span>
                        </div>

                        <div>
                            <h3 class="text-base font-black text-slate-900 line-clamp-1 font-heading">{{ $tmpl->name }}</h3>
                            <p class="text-xs text-slate-400 font-mono mt-0.5">{{ $tmpl->code }}</p>
                        </div>

                        <div class="p-3 bg-slate-50 rounded-xl text-xs space-y-1.5 text-slate-600 border border-slate-100">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">QR Code Verification:</span>
                                <span class="font-bold text-emerald-700"><i class="fa-solid fa-qrcode mr-1"></i> Position Configured</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400">Status:</span>
                                @if($tmpl->is_active)
                                    <span class="font-bold text-emerald-600"><i class="fa-solid fa-circle-check mr-1"></i> Active</span>
                                @else
                                    <span class="font-bold text-slate-400"><i class="fa-solid fa-circle-pause mr-1"></i> Disabled</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Actions --}}
                <div class="px-5 pb-5 pt-2 flex items-center justify-between gap-2 border-t border-slate-100">
                    <a href="{{ route('admin.certificates.templates.preview_pdf', $tmpl->id) }}" target="_blank" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                        <i class="fa-solid fa-file-pdf text-rose-500"></i>
                        <span>Preview PDF</span>
                    </a>

                    <a href="{{ route('admin.certificates.templates.edit', $tmpl->id) }}" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-xs transition">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Configure Layout</span>
                    </a>

                    @if($tmpl->certificates_count == 0)
                        <form action="{{ route('admin.certificates.templates.destroy', $tmpl->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this certificate template?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition" title="Delete Template">
                                <i class="fa-solid fa-trash-can text-xs"></i>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-12 border border-slate-200 text-center space-y-4">
                <div class="w-16 h-16 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl mx-auto shadow-xs">
                    <i class="fa-solid fa-certificate"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">No Certificate Templates Configured</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">Upload certificate background artwork to design automated dynamic certificates.</p>
                </div>
                <a href="{{ route('admin.certificates.templates.create') }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-xs transition">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Upload First Template</span>
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
