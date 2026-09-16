@extends('layouts.admin')

@section('title', 'Platform Settings & Authority Information')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-slate-900">Platform Settings & Authority Metadata</h1>
            <p class="text-sm text-slate-500">Configure confirmed association credentials, official system logo, address records, and system-wide configurations.</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-full border border-emerald-200">
                <i class="fa-solid fa-shield-halved"></i> Active System Config
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-800 text-sm font-semibold flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl text-rose-800 text-xs font-medium space-y-1">
            <div class="font-bold flex items-center gap-1.5 text-rose-700">
                <i class="fa-solid fa-triangle-exclamation"></i> Upload Error
            </div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.cms.settings.update') }}" enctype="multipart/form-data" class="space-y-6" id="settingsForm">
        @csrf

        <!-- 1. Official System Logo & Branding Box -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-5">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-image text-emerald-600"></i> Official System Logo & Brand Assets
                </h2>
                <span class="text-[11px] text-slate-400 font-semibold uppercase tracking-wider">Used on Certificates & ID Cards</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <!-- Current Logo Preview Card -->
                <div class="md:col-span-5 flex flex-col items-center justify-center p-6 bg-slate-900 rounded-2xl border border-slate-800 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-3 block">Current Active System Logo</span>
                    
                    <div class="h-28 w-full flex items-center justify-center p-3 bg-slate-950/80 rounded-xl border border-slate-800 relative" id="logoPreviewContainer">
                        @if(\App\Models\Setting::hasCustomLogo())
                            <img src="{{ \App\Models\Setting::getLogoUrl() }}" id="currentLogoImg" alt="System Logo" class="max-h-24 max-w-full object-contain filter drop-shadow-md">
                        @else
                            <div id="defaultBadge" class="flex flex-col items-center justify-center gap-1.5">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-700 flex items-center justify-center text-white font-black text-xl shadow-lg">
                                    TEV
                                </div>
                                <span class="text-[10px] font-bold text-emerald-400 font-mono tracking-wider">DEFAULT BADGE</span>
                            </div>
                        @endif
                    </div>

                    <p class="text-[11px] text-slate-400 mt-3">
                        @if(\App\Models\Setting::hasCustomLogo())
                            <span class="text-emerald-400 font-bold"><i class="fa-solid fa-circle-check"></i> Custom Logo Active</span>
                        @else
                            <span class="text-amber-400 font-medium"><i class="fa-solid fa-circle-info"></i> Using Default Emblem</span>
                        @endif
                    </p>

                    @if(\App\Models\Setting::hasCustomLogo())
                        <div class="mt-3 pt-3 border-t border-slate-800 w-full flex justify-center">
                            <label class="inline-flex items-center gap-2 text-xs font-semibold text-rose-400 hover:text-rose-300 cursor-pointer">
                                <input type="checkbox" name="remove_logo" value="1" class="rounded text-rose-600 focus:ring-rose-500 border-slate-700 bg-slate-800">
                                <span>Remove & Reset to Default</span>
                            </label>
                        </div>
                    @endif
                </div>

                <!-- Upload New Logo Input Area -->
                <div class="md:col-span-7 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Upload New System Logo (PNG, SVG, JPG, WEBP)</label>
                        <p class="text-xs text-slate-500 mb-3">
                            This logo is embedded directly on printed PDF Certificates, Member ID Cards, Invoices, Receipts, and Public Verification Pages.
                        </p>
                    </div>

                    <div class="border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-5 text-center transition bg-slate-50 hover:bg-emerald-50/20 relative cursor-pointer" onclick="document.getElementById('site_logo_input').click()">
                        <input type="file" name="site_logo" id="site_logo_input" accept="image/png,image/jpeg,image/svg+xml,image/webp" class="hidden" onchange="previewLogo(this)">
                        
                        <div class="space-y-2">
                            <div class="w-10 h-10 mx-auto rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center">
                                <i class="fa-solid fa-cloud-arrow-up text-lg"></i>
                            </div>
                            <div class="text-xs font-bold text-slate-700">
                                Click or drag image here to select new logo
                            </div>
                            <div class="text-[11px] text-slate-400 space-y-0.5">
                                <div>Recommended: <strong>Transparent PNG or Vector SVG</strong></div>
                                <div>Dimensions: <strong>400x120px (horizontal)</strong> or <strong>400x400px (square)</strong> • Max 5MB</div>
                            </div>
                        </div>

                        <div id="fileSelectedNotice" class="hidden mt-3 p-2 bg-emerald-100 border border-emerald-300 rounded-xl text-xs font-bold text-emerald-900">
                            Selected file: <span id="fileNameDisplay" class="font-mono"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. Chairman's Welcome Message & Executive Profile Box -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-user-tie text-emerald-600"></i> Chairman’s Welcome Message & Executive About Section
                </h2>
                <span class="text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full font-bold border border-emerald-200">
                    Live on Homepage
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                <!-- Chairman Photo Card -->
                <div class="md:col-span-4 flex flex-col items-center justify-center p-5 bg-slate-900 rounded-2xl border border-slate-800 text-center relative overflow-hidden">
                    <div class="absolute inset-0 opacity-10 pointer-events-none bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider mb-3 block">Chairman Official Portrait</span>
                    
                    <div class="h-44 w-36 rounded-2xl overflow-hidden border-2 border-emerald-500/50 shadow-xl relative bg-slate-950 flex items-center justify-center" id="chairmanPhotoPreviewContainer">
                        @if(\App\Models\Setting::hasChairmanPhoto())
                            <img src="{{ \App\Models\Setting::getChairmanPhotoUrl() }}" id="currentChairmanImg" alt="Dr. Charles Mwansasu" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-emerald-950 flex flex-col items-center justify-center text-emerald-400 p-2">
                                <i class="fa-solid fa-user text-4xl mb-2"></i>
                                <span class="text-[10px] font-bold">DR. CHARLES MWANSASU</span>
                            </div>
                        @endif
                    </div>

                    <p class="text-[11px] text-slate-300 mt-3 font-semibold">
                        {{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}
                    </p>
                    <span class="text-[10px] text-emerald-400">
                        {{ \App\Models\Setting::get('chairman_role', 'Organization Chair Man') }}
                    </span>

                    <div class="mt-3 pt-3 border-t border-slate-800 w-full flex flex-col items-center gap-2">
                        <button type="button" onclick="document.getElementById('chairman_photo_input').click()" class="w-full py-1.5 px-3 bg-slate-800 hover:bg-slate-700 text-white rounded-xl text-xs font-bold transition flex items-center justify-center gap-1.5 border border-slate-700">
                            <i class="fa-solid fa-camera text-emerald-400"></i> Change Photo
                        </button>
                        <input type="file" name="chairman_photo" id="chairman_photo_input" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewChairmanPhoto(this)">

                        <div id="chairmanFileSelectedNotice" class="hidden text-[10px] text-emerald-300 font-mono text-center">
                            Selected: <span id="chairmanFileName"></span>
                        </div>

                        <label class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-rose-400 hover:text-rose-300 cursor-pointer pt-1">
                            <input type="checkbox" name="remove_chairman_photo" value="1" class="rounded text-rose-600 focus:ring-rose-500 border-slate-700 bg-slate-800">
                            <span>Reset Photo to Default</span>
                        </label>
                    </div>
                </div>

                <!-- Executive Fields -->
                <div class="md:col-span-8 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Chairman Full Name</label>
                            <input type="text" name="chairman_name" value="{{ \App\Models\Setting::get('chairman_name', 'Dr. Charles Mwansasu') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Official Title / Designation</label>
                            <input type="text" name="chairman_role" value="{{ \App\Models\Setting::get('chairman_role', 'Organization Chair Man') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Organization Name</label>
                            <input type="text" name="chairman_organization" value="{{ \App\Models\Setting::get('chairman_organization', 'Tanzania Electric Vehicles Drivers Association (TEVDA)') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none font-medium">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">Message Title / Heading</label>
                            <input type="text" name="chairman_message_title" value="{{ \App\Models\Setting::get('chairman_message_title', 'Chairman’s Welcome Message') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none font-medium">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Welcome Slogan / Key Quote</label>
                        <input type="text" name="chairman_tagline" value="{{ \App\Models\Setting::get('chairman_tagline', 'Welcome to TEVDA—Smart Driving, Greener Future.') }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none font-medium">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-bold text-slate-800">
                                Full Official Welcome Message
                            </label>
                            <span class="text-[10px] text-slate-400">Supports paragraph breaks</span>
                        </div>
                        <textarea name="chairman_message" rows="7" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs leading-relaxed focus:ring-2 focus:ring-emerald-500 outline-none font-medium text-slate-800">{{ \App\Models\Setting::get('chairman_message') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">About Section Badge Label</label>
                            <input type="text" name="about_section_badge" value="{{ \App\Models\Setting::get('about_section_badge', 'Executive Welcome & About TEVDA') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-800 mb-1">About Section Main Heading</label>
                            <input type="text" name="about_section_title" value="{{ \App\Models\Setting::get('about_section_title', 'Shaping the Future of Electric Mobility in Tanzania') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Who We Are & Institutional Profile & SMART Principles Box -->
        <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h2 class="text-base font-black text-slate-900 flex items-center gap-2">
                    <i class="fa-solid fa-landmark text-emerald-600"></i> Institutional Profile ("Who We Are") & SMART Principles
                </h2>
                <span class="text-[11px] text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full font-bold border border-emerald-200">
                    Live on Public Website
                </span>
            </div>

            <!-- Who We Are Core Identity Fields -->
            <div class="space-y-4">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 font-heading">A. "Who We Are" Header & Profile Text</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Badge Label</label>
                        <input type="text" name="who_we_are_badge" value="{{ \App\Models\Setting::get('who_we_are_badge', 'Institutional Profile & Mandate') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">Section Main Title</label>
                        <input type="text" name="who_we_are_title" value="{{ \App\Models\Setting::get('who_we_are_title', 'Who We Are') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Main Institutional Definition / Profile</label>
                    <textarea name="who_we_are_description" rows="3" class="w-full p-3 bg-slate-50 border border-slate-200 rounded-xl text-xs leading-relaxed focus:ring-2 focus:ring-emerald-500 outline-none font-medium text-slate-800">{{ \App\Models\Setting::get('who_we_are_description') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-800 mb-1">Supporting Subtext / Mandate Summary</label>
                    <textarea name="who_we_are_subtext" rows="2" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs leading-relaxed focus:ring-2 focus:ring-emerald-500 outline-none">{{ \App\Models\Setting::get('who_we_are_subtext') }}</textarea>
                </div>
            </div>

            <!-- SMART Principles Fields -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <h3 class="text-xs font-black uppercase tracking-wider text-slate-500 font-heading">B. Our SMART Principles</h3>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">SMART Principles Title</label>
                        <input type="text" name="smart_principles_title" value="{{ \App\Models\Setting::get('smart_principles_title', 'Our SMART Principles') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-800 mb-1">SMART Principles Subtitle</label>
                        <input type="text" name="smart_principles_subtitle" value="{{ \App\Models\Setting::get('smart_principles_subtitle', 'The foundational pillars guiding every TEVDA driver, operator, and operational standard') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>
                </div>

                <div class="space-y-3 pt-2">
                    <!-- S -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white font-black text-xs flex items-center justify-center">S</span>
                            <span class="text-xs font-bold text-slate-800">Safety (Usalama)</span>
                        </div>
                        <input type="text" name="smart_s_desc" value="{{ \App\Models\Setting::get('smart_s_desc') }}" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                    </div>

                    <!-- M -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-cyan-600 text-white font-black text-xs flex items-center justify-center">M</span>
                            <span class="text-xs font-bold text-slate-800">Modern Tech (Teknolojia)</span>
                        </div>
                        <input type="text" name="smart_m_desc" value="{{ \App\Models\Setting::get('smart_m_desc') }}" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-cyan-500 outline-none">
                    </div>

                    <!-- A -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-indigo-600 text-white font-black text-xs flex items-center justify-center">A</span>
                            <span class="text-xs font-bold text-slate-800">Accountability (Uwajibikaji)</span>
                        </div>
                        <input type="text" name="smart_a_desc" value="{{ \App\Models\Setting::get('smart_a_desc') }}" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-indigo-500 outline-none">
                    </div>

                    <!-- R -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-amber-600 text-white font-black text-xs flex items-center justify-center">R</span>
                            <span class="text-xs font-bold text-slate-800">Respect (Heshima)</span>
                        </div>
                        <input type="text" name="smart_r_desc" value="{{ \App\Models\Setting::get('smart_r_desc') }}" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-amber-500 outline-none">
                    </div>

                    <!-- T -->
                    <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-6 h-6 rounded-lg bg-teal-600 text-white font-black text-xs flex items-center justify-center">T</span>
                            <span class="text-xs font-bold text-slate-800">Teamwork (Ushirikiano)</span>
                        </div>
                        <input type="text" name="smart_t_desc" value="{{ \App\Models\Setting::get('smart_t_desc') }}" class="w-full p-2 bg-white border border-slate-200 rounded-lg text-xs focus:ring-2 focus:ring-teal-500 outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. Other Group Settings -->
        @php
            $handledKeys = [
                'site_logo',
                'chairman_photo',
                'chairman_message',
                'chairman_name',
                'chairman_role',
                'chairman_organization',
                'chairman_tagline',
                'chairman_message_title',
                'about_section_badge',
                'about_section_title',
                'about_section_subtitle',
                'who_we_are_badge',
                'who_we_are_title',
                'who_we_are_description',
                'who_we_are_subtext',
                'smart_principles_badge',
                'smart_principles_title',
                'smart_principles_subtitle',
                'smart_s_title',
                'smart_s_desc',
                'smart_m_title',
                'smart_m_desc',
                'smart_a_title',
                'smart_a_desc',
                'smart_r_title',
                'smart_r_desc',
                'smart_t_title',
                'smart_t_desc',
                'smart_s',
                'smart_m',
                'smart_a',
                'smart_r',
                'smart_t',
            ];
        @endphp

        @foreach($settings as $groupName => $groupSettings)
            @php
                $unhandled = $groupSettings->whereNotIn('key', $handledKeys);
            @endphp

            @if($unhandled->count() > 0)
                <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-sm space-y-4">
                    <h2 class="text-base font-black text-slate-900 flex items-center gap-2 border-b border-slate-100 pb-3">
                        <i class="fa-solid fa-sliders text-emerald-600"></i> {{ ucwords(str_replace('_', ' ', $groupName)) }} Configurations
                    </h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($unhandled as $st)
                            <div class="{{ in_array($st->key, ['site_description', 'address', 'banner_message', 'hero_supporting_text', 'vision', 'mission', 'fraud_warning', 'about_section_subtitle']) ? 'sm:col-span-2' : '' }}">
                                <label class="block text-xs font-bold text-slate-700 mb-1">
                                    {{ ucwords(str_replace('_', ' ', $st->key)) }}
                                    @if($st->description)
                                        <span class="text-[10px] text-slate-400 font-normal">({{ $st->description }})</span>
                                    @endif
                                </label>
                                @if(in_array($st->key, ['site_description', 'address', 'banner_message', 'hero_supporting_text', 'vision', 'mission', 'fraud_warning', 'about_section_subtitle']) || $st->type === 'textarea')
                                    <textarea name="{{ $st->key }}" rows="3" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">{{ $st->value }}</textarea>
                                @else
                                    <input type="text" name="{{ $st->key }}" value="{{ $st->value }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500 outline-none">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        <div class="flex items-center justify-end sticky bottom-4 z-20">
            <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-bold transition shadow-lg shadow-emerald-600/30 flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Save All Settings & System Logo
            </button>
        </div>
    </form>
</div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('fileNameDisplay').textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
        document.getElementById('fileSelectedNotice').classList.remove('hidden');

        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('logoPreviewContainer');
            container.innerHTML = '<img src="' + e.target.result + '" alt="New Logo Preview" class="max-h-24 max-w-full object-contain filter drop-shadow-md">';
        };
        reader.readAsDataURL(file);
    }
}

function previewChairmanPhoto(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('chairmanFileName').textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
        document.getElementById('chairmanFileSelectedNotice').classList.remove('hidden');

        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('chairmanPhotoPreviewContainer');
            container.innerHTML = '<img src="' + e.target.result + '" alt="New Chairman Portrait" class="w-full h-full object-cover">';
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
