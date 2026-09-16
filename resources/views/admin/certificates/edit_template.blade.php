@extends('layouts.admin')

@section('title', 'Configure Certificate Template — ' . $template->name)

@section('content')
<div class="space-y-6">
    {{-- Header Bar --}}
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-4 sm:p-5 rounded-3xl border border-slate-200/80 shadow-xs">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.certificates.templates') }}" class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl font-black text-slate-900 font-heading">{{ $template->name }}</h1>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">
                        {{ strtoupper($template->orientation) }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 font-mono">Code: {{ $template->code }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            {{-- View Mode Switcher --}}
            <div class="inline-flex bg-slate-100 p-1 rounded-xl text-xs font-bold">
                <button type="button" id="btnModeEdit" onclick="switchViewMode('edit')" class="px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs">
                    <i class="fa-solid fa-arrows-up-down-left-right mr-1 text-emerald-600"></i> Interactive Editor
                </button>
                <button type="button" id="btnModePreview" onclick="switchViewMode('preview')" class="px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900">
                    <i class="fa-solid fa-eye mr-1 text-emerald-600"></i> Live Sample View
                </button>
            </div>

            <a href="{{ route('admin.certificates.templates.preview_pdf', $template->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                <i class="fa-solid fa-file-pdf text-rose-500"></i>
                <span>Preview PDF</span>
            </a>

            <button type="button" onclick="document.getElementById('templateForm').submit()" class="inline-flex items-center gap-1.5 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save Template Changes</span>
            </button>
        </div>
    </div>

    @include('partials.alerts')

    {{-- Main Workspace Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        {{-- Left Sidebar: Controls & Inspector (5 Cols) --}}
        <div class="lg:col-span-5 space-y-6">
            
            {{-- Presets & Auto-Detect Card --}}
            <div class="bg-white rounded-3xl p-5 border border-slate-200/80 shadow-xs space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i>
                        <span>Auto-Detect & Layout Presets</span>
                    </h3>
                    <span class="text-[10px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">1-Click Auto Align</span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <button type="button" onclick="applyPreset('standard_landscape')" class="p-2.5 rounded-xl border-2 border-emerald-500 bg-emerald-50/50 text-left transition group col-span-2">
                        <div class="font-black text-emerald-900 flex items-center justify-between">
                            <span><i class="fa-solid fa-certificate text-emerald-600 mr-1"></i> Official TEVDA Landscape</span>
                            <span class="text-[9px] bg-emerald-600 text-white font-bold px-1.5 py-0.5 rounded">Exact Graphic Fit</span>
                        </div>
                        <div class="text-[10px] text-slate-600 mt-0.5">Aligned to ribbon banner, gold seal, map & footer</div>
                    </button>
                    <button type="button" onclick="applyPreset('dual_column')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 text-left transition group">
                        <div class="font-bold text-slate-800 group-hover:text-emerald-700">Modern Dual-Column</div>
                        <div class="text-[10px] text-slate-400">Left details + Right QR</div>
                    </button>
                    <button type="button" onclick="applyPreset('portrait_standard')" class="p-2.5 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/40 text-left transition group">
                        <div class="font-bold text-slate-800 group-hover:text-emerald-700">Portrait Layout</div>
                        <div class="text-[10px] text-slate-400">Vertical A4 alignment</div>
                    </button>
                </div>
            </div>

            {{-- Element Selector & Inspector Panel --}}
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-5">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">
                        <i class="fa-solid fa-sliders text-emerald-600 mr-1.5"></i> Element Position & Style
                    </h3>
                    <span id="activeElementBadge" class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        Recipient Name
                    </span>
                </div>

                {{-- Element Selection Buttons --}}
                <div class="grid grid-cols-4 gap-1.5 text-xs font-bold">
                    <button type="button" onclick="selectElement('header')" id="tab_header" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700">
                        <i class="fa-solid fa-heading mr-1 text-emerald-600"></i> Header
                    </button>
                    <button type="button" onclick="selectElement('title')" id="tab_title" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Title
                    </button>
                    <button type="button" onclick="selectElement('recipient_name')" id="tab_recipient_name" class="elem-tab p-2 rounded-xl text-center transition bg-emerald-600 text-white shadow-xs">
                        Recipient
                    </button>
                    <button type="button" onclick="selectElement('body_text')" id="tab_body_text" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Body Text
                    </button>
                    <button type="button" onclick="selectElement('certificate_number')" id="tab_certificate_number" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Cert No
                    </button>
                    <button type="button" onclick="selectElement('issue_date')" id="tab_issue_date" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Date
                    </button>
                    <button type="button" onclick="selectElement('signatory')" id="tab_signatory" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700">
                        Signatory
                    </button>
                    <button type="button" onclick="selectElement('qr_code')" id="tab_qr_code" class="elem-tab p-2 rounded-xl text-center transition bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center gap-1">
                        <i class="fa-solid fa-qrcode text-emerald-600"></i> QR
                    </button>
                </div>

                {{-- Dynamic Controls for Active Element --}}
                <div class="space-y-4 pt-2">
                    
                    {{-- Visibility Toggle --}}
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl">
                        <label for="ctrl_enabled" class="text-xs font-bold text-slate-700">Display this element on certificate</label>
                        <input type="checkbox" id="ctrl_enabled" onchange="updateCurrentElement('enabled', this.checked)" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                    </div>

                    {{-- Position X (Left %) --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold mb-1">
                            <span class="text-slate-600">Horizontal Position (Left %):</span>
                            <span id="val_left" class="text-emerald-700 font-mono">50%</span>
                        </div>
                        <input type="range" id="ctrl_left" min="0" max="100" step="0.5" oninput="updateCurrentElement('left', parseFloat(this.value))" class="w-full accent-emerald-600">
                    </div>

                    {{-- Position Y (Top %) --}}
                    <div>
                        <div class="flex justify-between items-center text-xs font-bold mb-1">
                            <span class="text-slate-600">Vertical Position (Top %):</span>
                            <span id="val_top" class="text-emerald-700 font-mono">45%</span>
                        </div>
                        <input type="range" id="ctrl_top" min="0" max="100" step="0.5" oninput="updateCurrentElement('top', parseFloat(this.value))" class="w-full accent-emerald-600">
                    </div>

                    {{-- Text-Specific Controls --}}
                    <div id="textControlsGroup" class="space-y-4">
                        {{-- Width % --}}
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold mb-1">
                                <span class="text-slate-600">Element Width (%):</span>
                                <span id="val_width" class="text-emerald-700 font-mono">80%</span>
                            </div>
                            <input type="range" id="ctrl_width" min="10" max="100" step="1" oninput="updateCurrentElement('width', parseFloat(this.value))" class="w-full accent-emerald-600">
                        </div>

                        {{-- Font Size (pt) --}}
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold mb-1">
                                <span class="text-slate-600">Font Size:</span>
                                <span id="val_font_size" class="text-emerald-700 font-mono">14 pt</span>
                            </div>
                            <input type="range" id="ctrl_font_size" min="8" max="48" step="0.5" oninput="updateCurrentElement('font_size', parseFloat(this.value))" class="w-full accent-emerald-600">
                        </div>

                        {{-- Text Alignment & Font Weight --}}
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-bold text-slate-600 block mb-1">Text Align</label>
                                <div class="flex bg-slate-100 p-1 rounded-xl gap-1">
                                    <button type="button" onclick="updateCurrentElement('text_align', 'left')" id="align_left" class="p-1.5 rounded-lg text-xs flex-1 transition hover:bg-white text-slate-700"><i class="fa-solid fa-align-left"></i></button>
                                    <button type="button" onclick="updateCurrentElement('text_align', 'center')" id="align_center" class="p-1.5 rounded-lg text-xs flex-1 transition bg-white text-emerald-700 shadow-xs"><i class="fa-solid fa-align-center"></i></button>
                                    <button type="button" onclick="updateCurrentElement('text_align', 'right')" id="align_right" class="p-1.5 rounded-lg text-xs flex-1 transition hover:bg-white text-slate-700"><i class="fa-solid fa-align-right"></i></button>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-bold text-slate-600 block mb-1">Font Weight</label>
                                <select id="ctrl_font_weight" onchange="updateCurrentElement('font_weight', this.value)" class="w-full text-xs font-bold bg-slate-50 border border-slate-200 rounded-xl p-2 text-slate-800 focus:ring-emerald-500">
                                    <option value="normal">Normal</option>
                                    <option value="bold">Bold</option>
                                </select>
                            </div>
                        </div>

                        {{-- Text Color --}}
                        <div>
                            <label class="text-xs font-bold text-slate-600 block mb-1">Text Color</label>
                            <div class="flex items-center gap-2">
                                <input type="color" id="ctrl_color_picker" onchange="updateCurrentElement('color', this.value)" class="w-9 h-9 rounded-xl border border-slate-200 cursor-pointer p-0.5">
                                <input type="text" id="ctrl_color_text" onchange="updateCurrentElement('color', this.value)" class="w-full text-xs font-mono bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800">
                            </div>
                        </div>
                    </div>

                    {{-- QR Code Specific Controls --}}
                    <div id="qrControlsGroup" class="space-y-4 hidden">
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold mb-1">
                                <span class="text-slate-600">QR Code Size:</span>
                                <span id="val_qr_size" class="text-emerald-700 font-mono">65 px</span>
                            </div>
                            <input type="range" id="ctrl_qr_size" min="40" max="180" step="5" oninput="updateCurrentElement('size', parseInt(this.value))" class="w-full accent-emerald-600">
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl">
                            <label for="ctrl_qr_has_bg" class="text-xs font-bold text-slate-700">Add White Badge Container</label>
                            <input type="checkbox" id="ctrl_qr_has_bg" onchange="updateCurrentElement('has_bg', this.checked)" class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Template Metadata Settings Card --}}
            <div class="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200/80 shadow-xs space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 border-b border-slate-100 pb-3">
                    <i class="fa-solid fa-gear text-emerald-600 mr-1.5"></i> General Template Attributes
                </h3>

                <form id="templateForm" action="{{ route('admin.certificates.templates.update', $template->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="placeholders_config" id="hidden_placeholders_config">

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Template Name</label>
                        <input type="text" name="name" value="{{ old('name', $template->name) }}" required class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-900 font-bold focus:ring-emerald-500 focus:border-emerald-500">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Template Code</label>
                            <input type="text" name="code" value="{{ old('code', $template->code) }}" required class="w-full text-xs font-mono bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-900 font-bold uppercase focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Certificate Type</label>
                            <select name="certificate_type" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-900 font-bold focus:ring-emerald-500">
                                <option value="membership" {{ $template->certificate_type === 'membership' ? 'selected' : '' }}>Membership</option>
                                <option value="training_completion" {{ $template->certificate_type === 'training_completion' ? 'selected' : '' }}>Training Completion</option>
                                <option value="participation" {{ $template->certificate_type === 'participation' ? 'selected' : '' }}>Participation</option>
                                <option value="professional_certification" {{ $template->certificate_type === 'professional_certification' ? 'selected' : '' }}>Professional Certification</option>
                                <option value="recognition_appreciation" {{ $template->certificate_type === 'recognition_appreciation' ? 'selected' : '' }}>Recognition & Appreciation</option>
                                <option value="other" {{ $template->certificate_type === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Paper Orientation</label>
                        <select name="orientation" id="orientationSelect" onchange="changeOrientation(this.value)" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-2.5 text-slate-900 font-bold focus:ring-emerald-500">
                            <option value="landscape" {{ $template->orientation === 'landscape' ? 'selected' : '' }}>Landscape (Standard A4)</option>
                            <option value="portrait" {{ $template->orientation === 'portrait' ? 'selected' : '' }}>Portrait (Vertical A4)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Replace / Upload Background Artwork</label>
                        <input type="file" name="background_image" accept="image/*" onchange="updateCanvasBackground(this)" class="w-full text-xs bg-slate-50 border border-slate-200 rounded-xl p-2 text-slate-700 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-100 file:text-emerald-800 hover:file:bg-emerald-200">
                        <p class="text-[10px] text-slate-400 mt-1">Recommended size: 1536 x 1024 px (PNG / JPEG). Elements will layer on top.</p>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $template->is_active ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                        <label for="is_active" class="text-xs font-bold text-slate-700">Active Template (Available for issuance)</label>
                    </div>
                </form>
            </div>

        </div>

        {{-- Right Side: Interactive Visual Stage & Canvas Preview (7 Cols) --}}
        <div class="lg:col-span-7 sticky top-6 space-y-4">
            
            <div class="bg-slate-900/90 backdrop-blur-md rounded-3xl p-4 sm:p-5 text-white shadow-xl border border-slate-800">
                <div class="flex items-center justify-between mb-3 px-1">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-300 font-heading">Interactive Sheet View</span>
                    </div>
                    <span class="text-[11px] font-mono text-slate-400">Resolution: High-Fidelity 1:1 Vector</span>
                </div>

                {{-- The Certificate Viewport Container --}}
                <div id="canvasViewport" class="relative rounded-2xl overflow-hidden bg-slate-800/80 p-2 sm:p-4 flex items-center justify-center">
                    
                    {{-- Scaled Sheet Canvas --}}
                    <div id="certificateCanvas" class="relative w-full shadow-2xl bg-white transition-all overflow-hidden" style="aspect-ratio: {{ $template->orientation === 'portrait' ? '1 / 1.414' : '1.414 / 1' }};">
                        
                        {{-- Background Artwork Layer --}}
                        <div id="canvasBgLayer" class="absolute inset-0 w-full h-full pointer-events-none">
                            @if($template->background_image_path)
                                <img id="canvasBgImg" src="{{ $template->getBackgroundImageUrl() }}" class="w-full h-full object-cover" alt="Background Artwork">
                            @else
                                {{-- Default Vector Mock Border --}}
                                <div id="canvasDefaultBorder" class="w-full h-full border-8 border-emerald-800 p-2 box-border">
                                    <div class="w-full h-full border-2 border-amber-500 p-4 flex flex-col items-center">
                                        <div class="text-[12px] font-black tracking-widest text-emerald-800 uppercase mt-2">TEVDA OFFICIAL ACCREDITATION</div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Decorative Overlays (Top Right, Left Watermark & Footer) --}}
                        <div class="absolute top-[6.5%] right-[4.8%] text-right pointer-events-none font-times italic text-[9px] text-slate-800 leading-tight">
                            Driving<br>Clean Energy<br>for a Better<br>Tanzania
                        </div>
                        <div class="absolute top-[52.5%] left-[5.2%] text-left pointer-events-none font-arial font-bold text-[6px] tracking-widest text-emerald-800/60 uppercase leading-snug">
                            CLEAN<br>DRIVERS<br>GREENER<br>TOMORROW
                        </div>
                        {{-- Gold Seal Medallion area kept clean without overlay text --}}
                        <div class="absolute top-[93.0%] left-[19.0%] text-left pointer-events-none font-arial font-bold text-[5.5px] tracking-wider text-amber-200 uppercase">
                            PEOPLE &nbsp;|&nbsp; INNOVATION &nbsp;|&nbsp; CLEAN TRANSPORT &nbsp;|&nbsp; A GREENER TANZANIA
                        </div>
                        <div class="absolute top-[92.0%] right-[4.0%] text-right pointer-events-none font-script text-[8px] text-amber-200">
                            Electric Mobility for a Bright Future
                        </div>

                        {{-- Interactive Editable / Draggable Overlay Elements Layer --}}
                        <div id="canvasOverlayLayer" class="absolute inset-0 w-full h-full">
                            
                            {{-- Header & Association Branding Box --}}
                            <div id="elem_header" onclick="selectElement('header')" class="canvas-box absolute cursor-move group text-center font-times">
                                <div class="box-content-wrapper py-0.5">
                                    @if(\App\Models\Setting::getLogoUrl())
                                        <img src="{{ \App\Models\Setting::getLogoUrl() }}" class="max-h-12 max-w-[140px] mx-auto mb-1 block" alt="TEVDA Official Logo">
                                    @endif
                                    <div class="text-[11px] font-black text-emerald-900 uppercase tracking-widest leading-tight font-times">TANZANIA ELECTRIC VEHICLE DRIVERS ASSOCIATION</div>
                                    <div class="text-[7.5px] font-bold text-amber-600 tracking-wider font-times mt-0.5">SMART DRIVERS &bull; SMART MOBILITY</div>
                                </div>
                                <div class="box-handle-badge">Association Header</div>
                            </div>

                            {{-- Title Box --}}
                            <div id="elem_title" onclick="selectElement('title')" class="canvas-box absolute cursor-move group font-times">
                                <div class="box-content-wrapper">
                                    <span class="preview-text font-times font-bold text-amber-200 text-shadow">{{ $template->name }}</span>
                                </div>
                                <div class="box-handle-badge">Certificate Title</div>
                            </div>

                            {{-- Subtitle Box --}}
                            <div class="absolute top-[42.5%] left-[10%] w-[80%] text-center pointer-events-none font-arial font-bold text-[6.5px] tracking-widest text-slate-600 uppercase">
                                THIS IS OFFICIALLY PRESENTED TO
                            </div>

                            {{-- Recipient Box --}}
                            <div id="elem_recipient_name" onclick="selectElement('recipient_name')" class="canvas-box absolute cursor-move group font-times">
                                <div class="box-content-wrapper">
                                    <span class="preview-text font-times font-bold italic text-slate-900">Amina Hassan Test</span>
                                </div>
                                <div class="box-handle-badge">Recipient Name</div>
                            </div>

                            {{-- Body Text Box --}}
                            <div id="elem_body_text" onclick="selectElement('body_text')" class="canvas-box absolute cursor-move group font-arial">
                                <div class="box-content-wrapper">
                                    <span class="preview-text font-arial text-[7px] leading-tight text-slate-700">
                                        In recognition of meeting all constitutional requirements, driver qualifications, safety standards and curriculum benchmarks as prescribed under TEVDA governance framework.
                                        <div class="mt-0.5 text-[8px] text-slate-900 font-bold italic">Specialization / Course: Full Member</div>
                                    </span>
                                </div>
                                <div class="box-handle-badge">Body & Course Text</div>
                            </div>

                            {{-- Signatory Box --}}
                            <div id="elem_signatory" onclick="selectElement('signatory')" class="canvas-box absolute cursor-move group text-center">
                                <div class="box-content-wrapper">
                                    <div class="preview-text font-script text-xs leading-none text-slate-900">Dr. Charles Mwansasu</div>
                                    <div class="w-20 border-t border-slate-600 mx-auto my-0.5"></div>
                                    <div class="text-[7px] font-arial font-bold text-slate-900">Dr. Charles Mwansasu</div>
                                    <div class="text-[6px] font-arial text-slate-700">Founding Chairperson</div>
                                    <div class="text-[5.5px] font-arial text-slate-500">TEVDA Executive Council</div>
                                </div>
                                <div class="box-handle-badge">Signatory</div>
                            </div>

                            {{-- Issue Date & Cert Number Box --}}
                            <div id="elem_issue_date" onclick="selectElement('issue_date')" class="canvas-box absolute cursor-move group font-arial text-right text-[6.5px]">
                                <div class="box-content-wrapper leading-tight text-slate-700">
                                    <div>Issue Date: <strong class="text-slate-900 font-bold">15 Sep 2026</strong></div>
                                    <div>Expiry Date: <strong class="text-slate-900 font-bold">15 Sep 2027</strong></div>
                                    <div>Registry ID: <strong class="text-slate-900 font-normal">TEVDA-MEM-2026-000011</strong></div>
                                </div>
                                <div class="box-handle-badge">Metadata (Dates & ID)</div>
                            </div>

                            <div id="elem_certificate_number" onclick="selectElement('certificate_number')" class="canvas-box absolute cursor-move group font-arial text-right text-[6.5px]" style="display:none;">
                                <div class="box-content-wrapper">
                                    <span class="preview-text font-arial">Registry ID</span>
                                </div>
                            </div>

                            {{-- QR Code Box --}}
                            <div id="elem_qr_code" onclick="selectElement('qr_code')" class="canvas-box absolute cursor-move group flex flex-col items-center justify-center font-arial text-center">
                                <div id="qrContentWrapper" class="flex items-center justify-center p-0.5 bg-white rounded border border-slate-200">
                                    {!! $sampleQrSvg !!}
                                </div>
                                <div class="text-[5px] font-bold text-slate-700 mt-0.5">Scan to Verify</div>
                                <div class="box-handle-badge">QR Code</div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-[11px] text-slate-400 px-1 pt-1">
                    <span><i class="fa-solid fa-circle-info mr-1 text-emerald-500"></i> Drag items directly on canvas or use left sliders.</span>
                    <span>A4 Standard Layout: <strong>{{ ucfirst($template->orientation) }}</strong></span>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Google Fonts for Certificate Canvas --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Berkshire+Swash&family=Great+Vibes&display=swap" rel="stylesheet">

<style>
.font-berkshire {
    font-family: 'Berkshire Swash', 'Georgia', serif !important;
}
.font-script {
    font-family: 'Great Vibes', 'Alex Brush', cursive !important;
}
.font-times {
    font-family: 'Times New Roman', 'Times', serif !important;
}
.font-arial {
    font-family: 'Arial', 'Helvetica', sans-serif !important;
}
.canvas-box {
    box-sizing: border-box;
    transition: outline 0.15s ease, background-color 0.15s ease;
    outline: 1.5px dashed rgba(16, 185, 129, 0.4);
    background-color: rgba(16, 185, 129, 0.03);
    user-select: none;
}
.canvas-box:hover {
    outline: 1.5px solid rgba(16, 185, 129, 0.8);
    background-color: rgba(16, 185, 129, 0.07);
}
.canvas-box.active-box {
    outline: 2px solid #059669 !important;
    background-color: rgba(5, 150, 105, 0.1) !important;
    z-index: 50 !important;
}
.box-handle-badge {
    position: absolute;
    top: -16px;
    left: 0;
    font-size: 8px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    background: #059669;
    color: #ffffff;
    padding: 1px 4px;
    border-radius: 3px;
    display: none;
    pointer-events: none;
    white-space: nowrap;
}
.canvas-box.active-box .box-handle-badge,
.canvas-box:hover .box-handle-badge {
    display: block;
}

/* In Preview Mode: Hide bounding boxes and handles */
.preview-mode .canvas-box {
    outline: none !important;
    background-color: transparent !important;
    cursor: default !important;
}
.preview-mode .box-handle-badge {
    display: none !important;
}
</style>

{{-- Visual Coordinate Builder Javascript Engine --}}
<script>
// Initial configurations loaded from model
let config = {!! json_encode($template->placeholders_config ?? \App\Models\CertificateTemplate::getDefaultPlaceholdersConfig($template->orientation ?? 'landscape')) !!};
let currentElementKey = 'recipient_name';
let isPreviewMode = false;

// Default Presets
const presets = {
    standard_landscape: {
        header: { enabled: true, top: 5.2, left: 50.0, width: 84.0, font_size: 19.5, show_logo: true, text_align: 'center' },
        title: { enabled: true, top: 32.2, left: 50.0, width: 70.0, font_size: 27.0, font_weight: 'bold', color: '#fef08a', text_align: 'center' },
        recipient_name: { enabled: true, top: 45.2, left: 50.0, width: 80.0, font_size: 32.0, font_weight: 'bold', color: '#0f172a', text_align: 'center' },
        body_text: { enabled: true, top: 54.5, left: 50.0, width: 72.0, font_size: 10.5, font_weight: 'normal', color: '#334155', text_align: 'center' },
        signatory: { enabled: true, top: 65.5, left: 11.0, width: 22.0, font_size: 10.5, font_weight: 'bold', color: '#0f172a', text_align: 'center' },
        qr_code: { enabled: true, top: 71.5, left: 81.0, size: 65, bg_color: '#ffffff', has_bg: true },
        issue_date: { enabled: true, top: 63.0, left: 69.0, width: 23.0, font_size: 9.0, font_weight: 'normal', color: '#64748b', text_align: 'right' },
        certificate_number: { enabled: true, top: 63.0, left: 69.0, width: 23.0, font_size: 9.0, font_weight: 'bold', color: '#0f172a', text_align: 'right' }
    },
    dual_column: {
        header: { enabled: true, top: 5.0, left: 50.0, width: 90.0, font_size: 13, show_logo: true, text_align: 'center' },
        title: { enabled: true, top: 32.0, left: 10.0, width: 55.0, font_size: 26, font_weight: 'bold', color: '#fef08a', text_align: 'left' },
        recipient_name: { enabled: true, top: 45.0, left: 10.0, width: 55.0, font_size: 30, font_weight: 'bold', color: '#0f172a', text_align: 'left' },
        body_text: { enabled: true, top: 54.0, left: 10.0, width: 55.0, font_size: 10.5, font_weight: 'normal', color: '#334155', text_align: 'left' },
        signatory: { enabled: true, top: 65.5, left: 10.0, width: 28.0, font_size: 11.0, font_weight: 'bold', color: '#0f172a', text_align: 'left' },
        qr_code: { enabled: true, top: 68.0, left: 80.0, size: 65, bg_color: '#ffffff', has_bg: true },
        issue_date: { enabled: true, top: 62.0, left: 70.0, width: 25.0, font_size: 9.0, font_weight: 'normal', color: '#64748b', text_align: 'right' },
        certificate_number: { enabled: true, top: 62.0, left: 70.0, width: 25.0, font_size: 9.0, font_weight: 'bold', color: '#0f172a', text_align: 'right' }
    },
    portrait_standard: {
        header: { enabled: true, top: 7.5, left: 50.0, width: 90.0, font_size: 12.5, show_logo: true, text_align: 'center' },
        title: { enabled: true, top: 21.0, left: 50.0, width: 90.0, font_size: 30, font_weight: 'bold', color: '#0f2744', text_align: 'center' },
        recipient_name: { enabled: true, top: 32.5, left: 50.0, width: 90.0, font_size: 34, font_weight: 'bold', color: '#065f46', text_align: 'center' },
        body_text: { enabled: true, top: 44.5, left: 50.0, width: 86.0, font_size: 14.0, font_weight: 'normal', color: '#334155', text_align: 'center' },
        signatory: { enabled: true, top: 66.5, left: 8.5, width: 32.0, font_size: 14.5, font_weight: 'bold', color: '#0f172a', text_align: 'left' },
        qr_code: { enabled: true, top: 64.0, left: 50.0, size: 110, bg_color: '#ffffff', has_bg: true },
        issue_date: { enabled: true, top: 66.5, left: 61.5, width: 32.0, font_size: 11.5, font_weight: 'normal', color: '#64748b', text_align: 'right' },
        certificate_number: { enabled: true, top: 74.5, left: 61.5, width: 32.0, font_size: 11.0, font_weight: 'bold', color: '#0f172a', text_align: 'right' }
    }
};

// Initialize Canvas on page load
document.addEventListener('DOMContentLoaded', function() {
    renderCanvasElements();
    selectElement('recipient_name');
    setupDraggables();
});

// Switch Active Inspector Element
function selectElement(key) {
    currentElementKey = key;
    
    // Update tab highlights
    document.querySelectorAll('.elem-tab').forEach(t => {
        t.classList.remove('bg-emerald-600', 'text-white', 'shadow-xs');
        t.classList.add('bg-slate-100', 'text-slate-700');
    });
    const activeTab = document.getElementById('tab_' + key);
    if (activeTab) {
        activeTab.classList.remove('bg-slate-100', 'text-slate-700');
        activeTab.classList.add('bg-emerald-600', 'text-white', 'shadow-xs');
    }

    // Update active outline box on canvas
    document.querySelectorAll('.canvas-box').forEach(b => b.classList.remove('active-box'));
    const activeBox = document.getElementById('elem_' + key);
    if (activeBox) activeBox.classList.add('active-box');

    // Update inspector values
    const item = config[key] || {};
    document.getElementById('activeElementBadge').textContent = formatElementLabel(key);
    document.getElementById('ctrl_enabled').checked = item.enabled !== false;

    document.getElementById('ctrl_top').value = item.top || 0;
    document.getElementById('val_top').textContent = (item.top || 0) + '%';
    
    document.getElementById('ctrl_left').value = item.left || 0;
    document.getElementById('val_left').textContent = (item.left || 0) + '%';

    if (key === 'qr_code') {
        document.getElementById('textControlsGroup').classList.add('hidden');
        document.getElementById('qrControlsGroup').classList.remove('hidden');
        
        document.getElementById('ctrl_qr_size').value = item.size || 75;
        document.getElementById('val_qr_size').textContent = (item.size || 75) + ' px';
        document.getElementById('ctrl_qr_has_bg').checked = item.has_bg !== false;
    } else {
        document.getElementById('textControlsGroup').classList.remove('hidden');
        document.getElementById('qrControlsGroup').classList.add('hidden');

        document.getElementById('ctrl_width').value = item.width || 80;
        document.getElementById('val_width').textContent = (item.width || 80) + '%';

        document.getElementById('ctrl_font_size').value = item.font_size || 14;
        document.getElementById('val_font_size').textContent = (item.font_size || 14) + ' pt';

        document.getElementById('ctrl_font_weight').value = item.font_weight || 'normal';

        const color = item.color || '#0f172a';
        document.getElementById('ctrl_color_picker').value = color.length === 7 ? color : '#0f172a';
        document.getElementById('ctrl_color_text').value = color;

        // Alignment buttons
        const align = item.text_align || 'center';
        ['left', 'center', 'right'].forEach(a => {
            const btn = document.getElementById('align_' + a);
            if (btn) {
                if (a === align) {
                    btn.className = 'p-1.5 rounded-lg text-xs bg-white text-emerald-700 shadow-xs';
                } else {
                    btn.className = 'p-1.5 rounded-lg text-xs hover:bg-white transition text-slate-700';
                }
            }
        });
    }
}

// Update Active Element Properties
function updateCurrentElement(property, value) {
    if (!config[currentElementKey]) config[currentElementKey] = {};
    config[currentElementKey][property] = value;

    // Update value indicator text in inspector
    if (property === 'top') document.getElementById('val_top').textContent = value + '%';
    if (property === 'left') document.getElementById('val_left').textContent = value + '%';
    if (property === 'width') document.getElementById('val_width').textContent = value + '%';
    if (property === 'font_size') document.getElementById('val_font_size').textContent = value + ' pt';
    if (property === 'size') document.getElementById('val_qr_size').textContent = value + ' px';

    // Update alignment buttons if alignment changed
    if (property === 'text_align') {
        ['left', 'center', 'right'].forEach(a => {
            const btn = document.getElementById('align_' + a);
            if (btn) {
                btn.className = (a === value) ? 'p-1.5 rounded-lg text-xs bg-white text-emerald-700 shadow-xs' : 'p-1.5 rounded-lg text-xs hover:bg-white transition text-slate-700';
            }
        });
    }

    renderCanvasElement(currentElementKey);
    syncConfigToHiddenInput();
}

// Render All Elements to Canvas
function renderCanvasElements() {
    Object.keys(config).forEach(k => renderCanvasElement(k));
    syncConfigToHiddenInput();
}

// Render a Single Element on Canvas
function renderCanvasElement(key) {
    const el = document.getElementById('elem_' + key);
    const item = config[key];
    if (!el || !item) return;

    if (item.enabled === false) {
        el.style.display = 'none';
        return;
    }
    el.style.display = 'block';

    // Compute transforms for anchor positioning
    const align = item.text_align || 'left';
    let transform = 'none';
    if (align === 'center') {
        transform = 'translateX(-50%)';
    } else if (align === 'right') {
        transform = 'translateX(-100%)';
    }

    el.style.top = (item.top || 0) + '%';
    el.style.left = (item.left || 0) + '%';
    el.style.transform = transform;

    if (key === 'qr_code') {
        const size = item.size || 75;
        // Scale appropriately for preview sheet
        const previewSize = Math.max(32, size * 0.75);
        el.style.width = previewSize + 'px';
        el.style.height = previewSize + 'px';
        
        const qrWrap = document.getElementById('qrContentWrapper');
        if (qrWrap) {
            if (item.has_bg !== false) {
                qrWrap.style.backgroundColor = item.bg_color || '#ffffff';
                qrWrap.style.borderRadius = '4px';
                qrWrap.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
            } else {
                qrWrap.style.backgroundColor = 'transparent';
                qrWrap.style.boxShadow = 'none';
            }
        }
    } else {
        el.style.width = (item.width || 80) + '%';
        el.style.textAlign = align;
        el.style.color = item.color || '#0f172a';
        
        // Font size scaled for responsiveness
        const fontSizePt = item.font_size || 14;
        const scaledPx = Math.max(8, fontSizePt * 0.82);
        el.style.fontSize = scaledPx + 'px';
        el.style.fontWeight = item.font_weight || 'normal';
    }
}

// Synchronize JSON to hidden form input
function syncConfigToHiddenInput() {
    document.getElementById('hidden_placeholders_config').value = JSON.stringify(config);
}

// Apply Preset Layout
function applyPreset(presetName) {
    if (presets[presetName]) {
        config = JSON.parse(JSON.stringify(presets[presetName]));
        renderCanvasElements();
        selectElement(currentElementKey);
    }
}

// Switch between Edit Mode and Preview Mode
function switchViewMode(mode) {
    const canvasViewport = document.getElementById('canvasViewport');
    const btnEdit = document.getElementById('btnModeEdit');
    const btnPreview = document.getElementById('btnModePreview');

    if (mode === 'preview') {
        isPreviewMode = true;
        canvasViewport.classList.add('preview-mode');
        btnPreview.className = 'px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs';
        btnEdit.className = 'px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900';
    } else {
        isPreviewMode = false;
        canvasViewport.classList.remove('preview-mode');
        btnEdit.className = 'px-3 py-1.5 rounded-lg transition bg-white text-slate-900 shadow-xs';
        btnPreview.className = 'px-3 py-1.5 rounded-lg transition text-slate-600 hover:text-slate-900';
    }
}

// Handle Canvas Drag and Drop
function setupDraggables() {
    const canvas = document.getElementById('certificateCanvas');

    document.querySelectorAll('.canvas-box').forEach(box => {
        box.addEventListener('mousedown', function(e) {
            if (isPreviewMode) return;
            const key = box.id.replace('elem_', '');
            selectElement(key);

            e.preventDefault();
            const canvasRect = canvas.getBoundingClientRect();
            
            function onMouseMove(moveEvent) {
                const clientX = moveEvent.clientX;
                const clientY = moveEvent.clientY;

                let leftPct = ((clientX - canvasRect.left) / canvasRect.width) * 100;
                let topPct = ((clientY - canvasRect.top) / canvasRect.height) * 100;

                leftPct = Math.max(0, Math.min(100, Math.round(leftPct * 10) / 10));
                topPct = Math.max(0, Math.min(100, Math.round(topPct * 10) / 10));

                config[key].left = leftPct;
                config[key].top = topPct;

                document.getElementById('ctrl_left').value = leftPct;
                document.getElementById('val_left').textContent = leftPct + '%';
                document.getElementById('ctrl_top').value = topPct;
                document.getElementById('val_top').textContent = topPct + '%';

                renderCanvasElement(key);
                syncConfigToHiddenInput();
            }

            function onMouseUp() {
                window.removeEventListener('mousemove', onMouseMove);
                window.removeEventListener('mouseup', onMouseUp);
            }

            window.addEventListener('mousemove', onMouseMove);
            window.addEventListener('mouseup', onMouseUp);
        });
    });
}

// Change Orientation
function changeOrientation(orientation) {
    const canvas = document.getElementById('certificateCanvas');
    if (orientation === 'portrait') {
        canvas.style.aspectRatio = '1 / 1.414';
    } else {
        canvas.style.aspectRatio = '1.414 / 1';
    }
}

// Update Canvas Background on image select
function updateCanvasBackground(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let img = document.getElementById('canvasBgImg');
            if (!img) {
                const bgLayer = document.getElementById('canvasBgLayer');
                bgLayer.innerHTML = `<img id="canvasBgImg" src="${e.target.result}" class="w-full h-full object-cover" alt="Background Artwork">`;
            } else {
                img.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    }
}

function formatElementLabel(key) {
    const labels = {
        header: 'Association Header & Logo',
        recipient_name: 'Recipient Name',
        title: 'Certificate Title',
        body_text: 'Body / Course Description',
        certificate_number: 'Certificate Number',
        issue_date: 'Issue Date',
        signatory: 'Authorized Signatory',
        qr_code: 'Verification QR Code'
    };
    return labels[key] || key;
}
</script>
@endsection
