@extends('layouts.admin')

@section('title', 'ID Card Template Studio — ' . $template->name)

@push('styles')
<style>
    .card-canvas-container {
        width: 100%;
        max-width: 580px;
        aspect-ratio: 85.6 / 53.98;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        user-select: none;
    }
    .canvas-item {
        position: absolute;
        cursor: move;
        transition: outline 0.15s, box-shadow 0.15s;
        transform-origin: top left;
    }
    .canvas-item:hover {
        outline: 1.5px dashed rgba(16, 185, 129, 0.7);
    }
    .canvas-item.is-selected {
        outline: 2px solid #10b981 !important;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.25);
        z-index: 50 !important;
    }
</style>
@endpush

@section('content')
<div class="space-y-6" x-data="idCardStudio()">

    {{-- Top Header & Actions --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cards.templates') }}" class="w-10 h-10 rounded-2xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-black text-slate-900 font-heading">{{ $template->name }}</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                        {{ strtoupper($template->card_type) }}
                    </span>
                    @if($template->is_default)
                        <span class="text-[10px] font-bold text-amber-700 bg-amber-100 px-2 py-0.5 rounded">
                            <i class="fa-solid fa-star text-[9px] mr-0.5 text-amber-500"></i> Default
                        </span>
                    @endif
                </div>
                <p class="text-xs text-slate-400 font-mono">Code: {{ $template->code }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- Front / Back Side Switcher --}}
            <div class="inline-flex bg-slate-100 p-1 rounded-xl text-xs font-bold">
                <button type="button" @click="activeSide = 'front'" :class="activeSide === 'front' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa-solid fa-id-badge text-emerald-600"></i>
                    <span>Front Side</span>
                </button>
                <button type="button" @click="activeSide = 'back'" :class="activeSide === 'back' ? 'bg-white text-slate-900 shadow-xs' : 'text-slate-600 hover:text-slate-900'" class="px-3.5 py-1.5 rounded-lg transition flex items-center gap-1.5">
                    <i class="fa-solid fa-arrows-rotate text-emerald-600"></i>
                    <span>Back Side</span>
                </button>
            </div>

            <a href="{{ route('admin.cards.templates.preview_pdf', $template->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                <span>Preview PDF</span>
            </a>

            <button type="button" @click="saveTemplate()" class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl shadow-xs transition">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save Template Changes</span>
            </button>
        </div>
    </div>

    @include('partials.alerts')

    {{-- Main Workspace Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        {{-- Left Inspector Panel (5 Cols) --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- Auto-Detect & Presets --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i>
                        <span>Auto-Detect & Layout Presets</span>
                    </h3>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">1-Click Apply</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                    <button type="button" @click="applyPreset('official_standard')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 text-left transition">
                        <div class="font-bold text-slate-900">Official CR80</div>
                        <div class="text-[10px] text-slate-400">Left Photo + Center Info</div>
                    </button>
                    <button type="button" @click="applyPreset('preprinted_shell')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 text-left transition">
                        <div class="font-bold text-slate-900">Card Shell</div>
                        <div class="text-[10px] text-slate-400">Pre-printed card shell</div>
                    </button>
                    <button type="button" @click="applyPreset('right_photo')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 text-left transition">
                        <div class="font-bold text-slate-900">Right Photo</div>
                        <div class="text-[10px] text-slate-400">Right photo layout</div>
                    </button>
                </div>
            </div>

            {{-- Element Inspector --}}
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-sliders text-emerald-600"></i>
                        <span>Element Position & Style</span>
                    </h3>
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200 uppercase" x-text="activeElementKey.replace('_', ' ')"></span>
                </div>

                {{-- Front Elements Navigation (when Front active) --}}
                <template x-if="activeSide === 'front'">
                    <div class="grid grid-cols-4 gap-1.5 text-xs font-bold">
                        <button type="button" @click="activeElementKey = 'photo'" :class="activeElementKey === 'photo' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Photo</button>
                        <button type="button" @click="activeElementKey = 'full_name'" :class="activeElementKey === 'full_name' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Name</button>
                        <button type="button" @click="activeElementKey = 'membership_number'" :class="activeElementKey === 'membership_number' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Member ID</button>
                        <button type="button" @click="activeElementKey = 'category'" :class="activeElementKey === 'category' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Category</button>
                        <button type="button" @click="activeElementKey = 'region'" :class="activeElementKey === 'region' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Region</button>
                        <button type="button" @click="activeElementKey = 'expiry_date'" :class="activeElementKey === 'expiry_date' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Validity</button>
                        <button type="button" @click="activeElementKey = 'qr_code'" :class="activeElementKey === 'qr_code' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">QR Code</button>
                        <button type="button" @click="activeElementKey = 'header'" :class="activeElementKey === 'header' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Header</button>
                    </div>
                </template>

                {{-- Back Elements Navigation (when Back active) --}}
                <template x-if="activeSide === 'back'">
                    <div class="grid grid-cols-3 gap-1.5 text-xs font-bold">
                        <button type="button" @click="activeElementKey = 'magnetic_stripe'" :class="activeElementKey === 'magnetic_stripe' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Mag Stripe</button>
                        <button type="button" @click="activeElementKey = 'terms'" :class="activeElementKey === 'terms' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Terms</button>
                        <button type="button" @click="activeElementKey = 'signatory'" :class="activeElementKey === 'signatory' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Signatory</button>
                        <button type="button" @click="activeElementKey = 'helpline'" :class="activeElementKey === 'helpline' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Helpline</button>
                        <button type="button" @click="activeElementKey = 'barcode'" :class="activeElementKey === 'barcode' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Barcode</button>
                        <button type="button" @click="activeElementKey = 'return_notice'" :class="activeElementKey === 'return_notice' ? 'bg-emerald-600 text-white shadow-xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'" class="p-2 rounded-xl text-center transition">Return Notice</button>
                    </div>
                </template>

                {{-- Dynamic Controls for Selected Element --}}
                <div class="space-y-4 pt-2">
                    {{-- Enabled toggle --}}
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl">
                        <label class="text-xs font-bold text-slate-700">Display this element on card</label>
                        <input type="checkbox" x-model="activeElement.enabled" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    </div>

                    {{-- Position X (Left %) --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold mb-1">
                            <span class="text-slate-600">Horizontal Position (Left %):</span>
                            <span class="text-emerald-700 font-mono" x-text="activeElement.left + '%'"></span>
                        </div>
                        <input type="range" min="0" max="100" step="0.5" x-model.number="activeElement.left" class="w-full accent-emerald-600">
                    </div>

                    {{-- Position Y (Top %) --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold mb-1">
                            <span class="text-slate-600">Vertical Position (Top %):</span>
                            <span class="text-emerald-700 font-mono" x-text="activeElement.top + '%'"></span>
                        </div>
                        <input type="range" min="0" max="100" step="0.5" x-model.number="activeElement.top" class="w-full accent-emerald-600">
                    </div>

                    {{-- Width % --}}
                    <template x-if="activeElement.width !== undefined">
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold mb-1">
                                <span class="text-slate-600">Width (%):</span>
                                <span class="text-emerald-700 font-mono" x-text="activeElement.width + '%'"></span>
                            </div>
                            <input type="range" min="5" max="100" step="1" x-model.number="activeElement.width" class="w-full accent-emerald-600">
                        </div>
                    </template>

                    {{-- Font Size (pt) --}}
                    <template x-if="activeElement.font_size !== undefined">
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold mb-1">
                                <span class="text-slate-600">Font Size:</span>
                                <span class="text-emerald-700 font-mono" x-text="activeElement.font_size + ' pt'"></span>
                            </div>
                            <input type="range" min="4" max="24" step="0.2" x-model.number="activeElement.font_size" class="w-full accent-emerald-600">
                        </div>
                    </template>

                    {{-- Color & Quick Palette --}}
                    <template x-if="activeElement.color !== undefined">
                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-slate-600 block">Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" x-model="activeElement.color" class="w-8 h-8 rounded-lg border border-slate-200 cursor-pointer p-0.5">
                                <input type="text" x-model="activeElement.color" class="flex-grow py-1.5 px-3 text-xs font-mono rounded-xl border border-slate-200">
                            </div>
                            <div class="flex items-center gap-1.5 pt-1">
                                <button type="button" @click="activeElement.color = '#ffffff'" class="w-5 h-5 rounded-md bg-white border border-slate-300" title="White"></button>
                                <button type="button" @click="activeElement.color = '#f59e0b'" class="w-5 h-5 rounded-md bg-amber-500" title="Gold"></button>
                                <button type="button" @click="activeElement.color = '#34d399'" class="w-5 h-5 rounded-md bg-emerald-400" title="Emerald"></button>
                                <button type="button" @click="activeElement.color = '#38bdf8'" class="w-5 h-5 rounded-md bg-cyan-400" title="Cyan"></button>
                                <button type="button" @click="activeElement.color = '#0f172a'" class="w-5 h-5 rounded-md bg-slate-900" title="Dark Slate"></button>
                                <button type="button" @click="activeElement.color = '#cbd5e1'" class="w-5 h-5 rounded-md bg-slate-300" title="Muted Grey"></button>
                            </div>
                        </div>
                    </template>

                    {{-- Helpline System Integration Note --}}
                    <template x-if="activeElementKey === 'helpline'">
                        <div class="p-3 bg-emerald-50/70 border border-emerald-200 rounded-2xl space-y-1 text-xs text-emerald-900">
                            <div class="font-bold flex items-center gap-1.5">
                                <i class="fa-solid fa-headset text-emerald-600"></i>
                                <span>Live System Contact Details</span>
                            </div>
                            <p class="text-[11px] text-emerald-800 leading-relaxed">
                                Content (Address, Helpline Phone, Email, & Website) is synchronized live with <strong>Platform Settings &gt; Contact Configurations</strong>.
                            </p>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Background Image Replacement Form --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200 shadow-xs space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-image text-emerald-600"></i>
                    <span>Replace Background Artwork</span>
                </h3>

                <form id="bgUploadForm" action="{{ route('admin.cards.templates.update', $template->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3 text-xs">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="name" value="{{ $template->name }}">
                    <input type="hidden" name="code" value="{{ $template->code }}">
                    <input type="hidden" name="card_type" value="{{ $template->card_type }}">
                    <input type="hidden" name="is_active" value="{{ $template->is_active ? 1 : 0 }}">
                    <input type="hidden" name="is_default" value="{{ $template->is_default ? 1 : 0 }}">

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Replace Front Artwork (PNG/JPG)</label>
                        <input type="file" name="front_background_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Replace Back Artwork (PNG/JPG)</label>
                        <input type="file" name="back_background_image" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100">
                    </div>

                    <button type="submit" class="w-full py-2 px-3 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-xl transition">
                        Upload & Update Backgrounds
                    </button>
                </form>
            </div>

        </div>

        {{-- Right Visual Canvas Area (7 Cols) --}}
        <div class="lg:col-span-7 space-y-4 lg:sticky lg:top-8">
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-bold font-heading text-slate-900 flex items-center gap-2">
                            <span>Visual Live Canvas</span>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded uppercase" x-text="activeSide + ' side'"></span>
                        </h2>
                        <p class="text-[11px] text-slate-400">Click any element directly on the card to inspect and reposition it.</p>
                    </div>

                    <div class="text-xs text-slate-400 font-mono">
                        CR80: 85.6mm × 54mm
                    </div>
                </div>

                {{-- The Canvas Container --}}
                <div class="flex justify-center py-2">
                    <div class="card-canvas-container border border-emerald-500/40 relative">
                        
                        {{-- ==================== FRONT CANVAS ==================== --}}
                        <div x-show="activeSide === 'front'" class="w-full h-full relative">
                            {{-- Background Layer --}}
                            @if($template->front_background_image_path)
                                <img src="{{ asset('storage/' . $template->front_background_image_path) }}" alt="Front BG" class="w-full h-full object-cover absolute inset-0">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 absolute inset-0"></div>
                            @endif

                            {{-- Canvas Items --}}
                            {{-- Header / Logo --}}
                            <div x-show="config.front.header.enabled"
                                @click="selectElement('header')"
                                :class="{'is-selected': activeElementKey === 'header'}"
                                :style="`top: ${config.front.header.top}%; left: ${config.front.header.left}%;`"
                                class="canvas-item p-1 flex items-center gap-1.5 text-white">
                                <template x-if="config.front.header.show_logo">
                                    <img src="{{ $logoUrl }}" alt="Logo" class="h-6 max-w-[32px] object-contain">
                                </template>
                                <div>
                                    <div class="font-black leading-none text-[11px]" :style="`color: ${config.front.header.color}; font-size: ${config.front.header.font_size}px;`" x-text="config.front.header.title"></div>
                                    <div class="text-[6.5px] font-bold uppercase tracking-wider text-emerald-400" x-text="config.front.header.subtitle"></div>
                                </div>
                            </div>

                            {{-- Category Badge --}}
                            <div x-show="config.front.category.enabled"
                                @click="selectElement('category')"
                                :class="{'is-selected': activeElementKey === 'category'}"
                                :style="`top: ${config.front.category.top}%; left: ${config.front.category.left}%;`"
                                class="canvas-item p-1">
                                <span class="font-bold uppercase tracking-wider px-2 py-0.5 rounded text-[8px]"
                                    :style="`color: ${config.front.category.color}; background: ${config.front.category.bg_color}; border: 1px solid ${config.front.category.border_color};`">
                                    {{ $sampleMember->category->name }}
                                </span>
                            </div>

                            {{-- Photo --}}
                            <div x-show="config.front.photo.enabled"
                                @click="selectElement('photo')"
                                :class="{'is-selected': activeElementKey === 'photo'}"
                                :style="`top: ${config.front.photo.top}%; left: ${config.front.photo.left}%; width: ${config.front.photo.width}%; height: ${config.front.photo.height}%; border-radius: ${config.front.photo.border_radius}px; border: ${config.front.photo.border_width}px solid ${config.front.photo.border_color};`"
                                class="canvas-item overflow-hidden bg-slate-900 shadow-md">
                                @if($sampleMember->passport_photo_path)
                                    <img src="{{ asset('storage/' . $sampleMember->passport_photo_path) }}" alt="Photo" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center font-bold text-[8px] text-slate-400">PHOTO</div>
                                @endif
                            </div>

                            {{-- Full Name --}}
                            <div x-show="config.front.full_name.enabled"
                                @click="selectElement('full_name')"
                                :class="{'is-selected': activeElementKey === 'full_name'}"
                                :style="`top: ${config.front.full_name.top}%; left: ${config.front.full_name.left}%; width: ${config.front.full_name.width}%; text-align: ${config.front.full_name.text_align};`"
                                class="canvas-item p-1">
                                <span class="text-[7px] uppercase text-slate-400 block font-semibold leading-none" x-text="config.front.full_name.label_text"></span>
                                <strong class="font-black leading-tight block truncate uppercase"
                                    :style="`color: ${config.front.full_name.color}; font-size: ${config.front.full_name.font_size * 1.3}px;`">
                                    {{ $sampleMember->full_name }}
                                </strong>
                            </div>

                            {{-- Member Number --}}
                            <div x-show="config.front.membership_number.enabled"
                                @click="selectElement('membership_number')"
                                :class="{'is-selected': activeElementKey === 'membership_number'}"
                                :style="`top: ${config.front.membership_number.top}%; left: ${config.front.membership_number.left}%;`"
                                class="canvas-item p-1">
                                <span class="text-[7px] uppercase text-slate-400 block font-semibold leading-none" x-text="config.front.membership_number.label_text"></span>
                                <span class="font-mono font-bold text-xs inline-block px-1.5 py-0.5 rounded"
                                    :style="`color: ${config.front.membership_number.color}; background: ${config.front.membership_number.bg_color}; border: 1px solid ${config.front.membership_number.border_color}; font-size: ${config.front.membership_number.font_size * 1.2}px;`">
                                    {{ $sampleMember->membership_number }}
                                </span>
                            </div>

                            {{-- Region --}}
                            <div x-show="config.front.region.enabled"
                                @click="selectElement('region')"
                                :class="{'is-selected': activeElementKey === 'region'}"
                                :style="`top: ${config.front.region.top}%; left: ${config.front.region.left}%; width: ${config.front.region.width}%;`"
                                class="canvas-item p-1">
                                <span class="text-[7px] uppercase text-slate-400 block font-semibold leading-none" x-text="config.front.region.label_text"></span>
                                <span class="font-semibold block text-[10px]" :style="`color: ${config.front.region.color};`">
                                    {{ $sampleMember->region?->name ?? 'Dar es Salaam' }} • Ubungo
                                </span>
                            </div>

                            {{-- Validity --}}
                            <div x-show="config.front.expiry_date.enabled"
                                @click="selectElement('expiry_date')"
                                :class="{'is-selected': activeElementKey === 'expiry_date'}"
                                :style="`top: ${config.front.expiry_date.top}%; left: ${config.front.expiry_date.left}%;`"
                                class="canvas-item p-1">
                                <span class="text-[7px] uppercase text-slate-400 block font-semibold leading-none" x-text="config.front.expiry_date.label_text"></span>
                                <span class="font-bold text-[9.5px]" :style="`color: ${config.front.expiry_date.color};`">
                                    EXP: {{ date('m/Y', strtotime('+1 year')) }} <span class="text-emerald-400 ml-1">• VERIFIED</span>
                                </span>
                            </div>

                            {{-- QR Code --}}
                            <div x-show="config.front.qr_code.enabled"
                                @click="selectElement('qr_code')"
                                :class="{'is-selected': activeElementKey === 'qr_code'}"
                                :style="`top: ${config.front.qr_code.top}%; left: ${config.front.qr_code.left}%;`"
                                class="canvas-item p-1 flex flex-col items-center">
                                <div class="bg-white p-1 rounded-xl shadow-md border border-slate-200">
                                    <img src="{{ $qrCodeUri }}" alt="QR" :style="`width: ${config.front.qr_code.size}px; height: ${config.front.qr_code.size}px;`">
                                </div>
                                <span class="text-[6px] uppercase tracking-wider text-slate-400 font-bold mt-1" x-text="config.front.qr_code.label_text"></span>
                            </div>

                            {{-- Motto / Footer --}}
                            <div x-show="config.front.motto.enabled"
                                @click="selectElement('motto')"
                                :class="{'is-selected': activeElementKey === 'motto'}"
                                :style="`top: ${config.front.motto.top}%; left: ${config.front.motto.left}%; width: ${config.front.motto.width}%; text-align: ${config.front.motto.text_align}; transform: ${config.front.motto.text_align === 'center' ? 'translateX(-50%)' : 'none'};`"
                                class="canvas-item p-1">
                                <span class="font-bold uppercase tracking-wider text-[7.5px]" :style="`color: ${config.front.motto.color};`" x-text="config.front.motto.text"></span>
                            </div>
                        </div>

                        {{-- ==================== BACK CANVAS ==================== --}}
                        <div x-show="activeSide === 'back'" class="w-full h-full relative">
                            @if($template->back_background_image_path)
                                <img src="{{ asset('storage/' . $template->back_background_image_path) }}" alt="Back BG" class="w-full h-full object-cover absolute inset-0">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 absolute inset-0"></div>
                            @endif

                            {{-- Mag Stripe --}}
                            <div x-show="config.back.magnetic_stripe.enabled"
                                @click="selectElement('magnetic_stripe')"
                                :class="{'is-selected': activeElementKey === 'magnetic_stripe'}"
                                :style="`top: ${config.back.magnetic_stripe.top}%; left: 0; width: 100%; height: ${config.back.magnetic_stripe.height * 2.5}px; background: ${config.back.magnetic_stripe.bg_color};`"
                                class="canvas-item px-4 flex items-center justify-between">
                                <span class="font-mono text-[8px] font-bold" :style="`color: ${config.back.magnetic_stripe.color};`">
                                    CARD ID: CARD-{{ $sampleMember->membership_number }}
                                </span>
                                <span class="text-[7px] text-emerald-400 font-bold uppercase">OFFICIAL SMART BADGE</span>
                            </div>

                            {{-- Terms --}}
                            <div x-show="config.back.terms.enabled"
                                @click="selectElement('terms')"
                                :class="{'is-selected': activeElementKey === 'terms'}"
                                :style="`top: ${config.back.terms.top}%; left: ${config.back.terms.left}%; width: ${config.back.terms.width}%;`"
                                class="canvas-item p-1">
                                <p class="text-[7.5px] leading-relaxed text-justify" :style="`color: ${config.back.terms.color};`" x-text="config.back.terms.text"></p>
                            </div>

                            {{-- Signatory --}}
                            <div x-show="config.back.signatory.enabled"
                                @click="selectElement('signatory')"
                                :class="{'is-selected': activeElementKey === 'signatory'}"
                                :style="`top: ${config.back.signatory.top}%; left: ${config.back.signatory.left}%; width: ${config.back.signatory.width}%;`"
                                class="canvas-item p-1 text-center space-y-0.5">
                                <span class="text-[6.5px] uppercase text-slate-400 block font-semibold">Authorized Signatory</span>
                                <div class="border-b border-slate-600 pb-0.5">
                                    <span class="font-serif italic text-sm text-emerald-400">Charles Mwansasu</span>
                                </div>
                                <span class="text-[7.5px] font-bold block" :style="`color: ${config.back.signatory.color};`" x-text="config.back.signatory.name"></span>
                                <span class="text-[6.5px] text-slate-400 block" x-text="config.back.signatory.title"></span>
                            </div>

                            {{-- Helpline --}}
                            <div x-show="config.back.helpline.enabled"
                                @click="selectElement('helpline')"
                                :class="{'is-selected': activeElementKey === 'helpline'}"
                                :style="`top: ${config.back.helpline.top}%; left: ${config.back.helpline.left}%; width: ${config.back.helpline.width}%;`"
                                class="canvas-item p-2 rounded-xl bg-slate-900/90 text-[7px] leading-tight space-y-0.5 border border-white/5" :style="`color: ${config.back.helpline.color};`">
                                <strong class="text-emerald-400 block font-bold text-[7.5px]">{{ \App\Models\Setting::get('site_short_name', 'TEVDA') }} HEADQUARTERS</strong>
                                <p>{{ \App\Models\Setting::get('contact_address', 'Sinza Mori, P.O. Box 40015, Dar es Salaam, Tanzania') }}</p>
                                <p class="font-mono font-bold">Helpline: {{ \App\Models\Setting::get('contact_phone', '+255 757 700 401') }}</p>
                                <p>Support: {{ \App\Models\Setting::get('contact_email', 'info@tevda.or.tz') }} • {{ \App\Models\Setting::get('contact_website', 'www.tevda.or.tz') }}</p>
                            </div>

                            {{-- Barcode --}}
                            <div x-show="config.back.barcode.enabled"
                                @click="selectElement('barcode')"
                                :class="{'is-selected': activeElementKey === 'barcode'}"
                                :style="`top: ${config.back.barcode.top}%; left: ${config.back.barcode.left}%; width: ${config.back.barcode.width}%; transform: translateX(-50%);`"
                                class="canvas-item p-1 text-center font-mono text-[8px] tracking-widest" :style="`color: ${config.back.barcode.color};`">
                                ||| | |||| | ||| |||| | || ||| |||| | || | |||
                            </div>

                            {{-- Return Notice --}}
                            <div x-show="config.back.return_notice.enabled"
                                @click="selectElement('return_notice')"
                                :class="{'is-selected': activeElementKey === 'return_notice'}"
                                :style="`top: ${config.back.return_notice.top}%; left: ${config.back.return_notice.left}%; width: ${config.back.return_notice.width}%; transform: translateX(-50%);`"
                                class="canvas-item p-1 text-center text-[6.5px] uppercase tracking-wider" :style="`color: ${config.back.return_notice.color};`">
                                <span x-text="config.back.return_notice.text"></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="text-center">
                    <p class="text-[11px] text-slate-500">
                        <span class="font-bold text-slate-700">Tip:</span> Switch between Front & Back tabs on top right. Use the sliders on the left to fine-tune exact positions.
                    </p>
                </div>
            </div>
        </div>

    </div>

    {{-- Hidden form to save placeholder configs --}}
    <form id="saveConfigForm" action="{{ route('admin.cards.templates.update', $template->id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="name" value="{{ $template->name }}">
        <input type="hidden" name="code" value="{{ $template->code }}">
        <input type="hidden" name="card_type" value="{{ $template->card_type }}">
        <input type="hidden" name="is_active" value="{{ $template->is_active ? 1 : 0 }}">
        <input type="hidden" name="is_default" value="{{ $template->is_default ? 1 : 0 }}">
        <input type="hidden" name="placeholders_config" id="placeholders_config">
    </form>

</div>
@endsection

@push('scripts')
<script>
function idCardStudio() {
    return {
        activeSide: 'front',
        activeElementKey: 'photo',
        config: @json($template->placeholders_config),
        presets: @json($presets),

        get activeElement() {
            if (!this.config[this.activeSide]) {
                this.config[this.activeSide] = {};
            }
            if (!this.config[this.activeSide][this.activeElementKey]) {
                this.config[this.activeSide][this.activeElementKey] = { enabled: true, top: 20, left: 20 };
            }
            return this.config[this.activeSide][this.activeElementKey];
        },

        selectElement(key) {
            this.activeElementKey = key;
        },

        applyPreset(presetKey) {
            if (this.presets[presetKey] && this.presets[presetKey].config) {
                this.config = JSON.parse(JSON.stringify(this.presets[presetKey].config));
            }
        },

        saveTemplate() {
            document.getElementById('placeholders_config').value = JSON.stringify(this.config);
            document.getElementById('saveConfigForm').submit();
        }
    };
}
</script>
@endpush
