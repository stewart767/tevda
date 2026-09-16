@extends('layouts.admin')

@section('title', 'Upload ID Card Template Background')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2 text-xs text-slate-500">
            <a href="{{ route('admin.cards.templates') }}" class="hover:text-emerald-600 transition">ID Card Templates</a>
            <span>/</span>
            <span class="text-slate-900 font-semibold">Upload New Template</span>
        </div>
        <a href="{{ route('admin.cards.templates') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 transition flex items-center gap-1">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Back to Templates</span>
        </a>
    </div>

    {{-- Main Upload Form Card --}}
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        <div>
            <h1 class="text-xl font-black font-heading text-slate-900 tracking-tight">Upload ID Card Template & Background Artwork</h1>
            <p class="text-xs text-slate-500 mt-1">Upload front and back background artwork images (standard ISO/IEC 7810 CR80 ratio: 1.586). The system will automatically configure placeholders and layout coordinates.</p>
        </div>

        @include('partials.alerts')

        <form action="{{ route('admin.cards.templates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- 1. Template Metadata --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Template Name <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="e.g. Official TEVDA Plastic Card 2026" required class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Template Code / Slug <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" value="{{ old('code') }}" placeholder="e.g. cr80_official_2026" required class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition font-mono font-bold">
                    <p class="text-[10px] text-slate-400 mt-1">Unique alphanumeric identifier</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Card Category / Type <span class="text-rose-500">*</span>
                    </label>
                    <select name="card_type" required class="w-full py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white transition font-medium">
                        <option value="standard" {{ old('card_type') === 'standard' ? 'selected' : '' }}>Standard Member ID Card</option>
                        <option value="commercial" {{ old('card_type') === 'commercial' ? 'selected' : '' }}>Commercial EV Driver Card</option>
                        <option value="executive" {{ old('card_type') === 'executive' ? 'selected' : '' }}>Executive / VIP Member Card</option>
                        <option value="membership" {{ old('card_type') === 'membership' ? 'selected' : '' }}>Association Membership Card</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Physical Card Format
                    </label>
                    <div class="py-2.5 px-3.5 text-xs rounded-xl border border-slate-200 bg-slate-100 text-slate-700 font-mono font-bold flex items-center justify-between">
                        <span>ISO/IEC 7810 ID-1 (CR80)</span>
                        <span class="text-[10px] text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">85.6mm × 54mm</span>
                    </div>
                </div>
            </div>

            {{-- 2. Background Artwork Upload (Front & Back) --}}
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                    <i class="fa-solid fa-image text-emerald-600"></i>
                    <span>Background Artwork Images (Front & Back)</span>
                </h3>
                <p class="text-xs text-slate-500">Recommended resolution: <strong>1011 × 638 pixels</strong> (or higher, 300 DPI, CR80 aspect ratio: 1.586). PNG or high-quality JPG.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Front Side Upload --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">
                            Front Side Artwork (Background) <span class="text-slate-400 text-[10px]">(Recommended)</span>
                        </label>
                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center hover:border-emerald-500 transition bg-slate-50/50">
                            <input type="file" name="front_background_image" id="front_background_image" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewFile(this, 'frontPreview', 'frontPlaceholder')">
                            <label for="front_background_image" class="cursor-pointer block space-y-2">
                                <div id="frontPlaceholder" class="space-y-1">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg mx-auto">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 block">Click to upload Front Background</span>
                                    <span class="text-[10px] text-slate-400 block">PNG, JPG, WEBP up to 10MB</span>
                                </div>
                                <div id="frontPreview" class="hidden relative rounded-xl overflow-hidden shadow-xs border border-slate-200" style="aspect-ratio: 85.6 / 53.98;">
                                    <img src="" alt="Front Preview" class="w-full h-full object-cover">
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Back Side Upload --}}
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700">
                            Back Side Artwork (Background) <span class="text-slate-400 text-[10px]">(Optional)</span>
                        </label>
                        <div class="border-2 border-dashed border-slate-300 rounded-2xl p-4 text-center hover:border-emerald-500 transition bg-slate-50/50">
                            <input type="file" name="back_background_image" id="back_background_image" accept="image/png,image/jpeg,image/webp" class="hidden" onchange="previewFile(this, 'backPreview', 'backPlaceholder')">
                            <label for="back_background_image" class="cursor-pointer block space-y-2">
                                <div id="backPlaceholder" class="space-y-1">
                                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center text-lg mx-auto">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 block">Click to upload Back Background</span>
                                    <span class="text-[10px] text-slate-400 block">PNG, JPG, WEBP up to 10MB</span>
                                </div>
                                <div id="backPreview" class="hidden relative rounded-xl overflow-hidden shadow-xs border border-slate-200" style="aspect-ratio: 85.6 / 53.98;">
                                    <img src="" alt="Back Preview" class="w-full h-full object-cover">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Auto-Detect & Layout Preset Picker --}}
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800 flex items-center gap-1.5">
                        <i class="fa-solid fa-wand-magic-sparkles text-emerald-600"></i>
                        <span>Initial Layout Preset (Auto-Detect Engine)</span>
                    </h3>
                    <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded-full">Adjustable later in Studio</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($presets as $key => $preset)
                        <label class="cursor-pointer border-2 rounded-2xl p-3.5 transition flex flex-col justify-between hover:border-emerald-500 hover:bg-emerald-50/30">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <input type="radio" name="preset" value="{{ $key }}" {{ $loop->first ? 'checked' : '' }} class="w-4 h-4 text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Preset {{ $loop->iteration }}</span>
                                </div>
                                <strong class="text-xs font-bold text-slate-900 block">{{ $preset['name'] }}</strong>
                                <p class="text-[10px] text-slate-500 leading-tight">{{ $preset['description'] }}</p>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- 4. Status & Default Toggles --}}
            <div class="space-y-3 pt-2 border-t border-slate-100">
                <div class="flex flex-wrap items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                        <span class="text-xs font-bold text-slate-700">Activate this template immediately</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_default" value="1" class="w-4 h-4 text-amber-600 rounded focus:ring-amber-500">
                        <span class="text-xs font-bold text-slate-700">Set as default template for all new ID Cards</span>
                    </label>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <span class="text-xs text-slate-500">You will be redirected to the Visual Studio to fine-tune field positions.</span>
                <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm px-6 py-3 rounded-xl shadow-md transition">
                    <i class="fa-solid fa-cloud-arrow-up"></i>
                    <span>Create Template & Open Studio</span>
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
function previewFile(input, previewId, placeholderId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);
            preview.querySelector('img').src = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    }
}

document.getElementById('name').addEventListener('input', function() {
    const codeInput = document.getElementById('code');
    if (!codeInput.dataset.touched) {
        codeInput.value = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
    }
});

document.getElementById('code').addEventListener('input', function() {
    this.dataset.touched = 'true';
});
</script>
@endpush
