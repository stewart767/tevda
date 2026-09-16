@extends('layouts.admin')

@section('title', 'Upload Certificate Template')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.certificates.templates') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition shadow-xs">
            <i class="fa-solid fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-900 font-heading">Upload Certificate Template</h1>
            <p class="text-xs text-slate-500">Upload background artwork and the system will automatically configure coordinate overlays for dynamic contents and QR code.</p>
        </div>
    </div>

    @include('partials.alerts')

    <form action="{{ route('admin.certificates.templates.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl border border-slate-200/80 shadow-xs p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Template Name --}}
            <div class="md:col-span-2">
                <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Template Name <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="name" name="name" required value="{{ old('name') }}" placeholder="e.g. Master Commercial EV Driver Certification" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                @error('name')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Template Code --}}
            <div>
                <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Template Code <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="code" name="code" required value="{{ old('code') }}" placeholder="e.g. CERT-EV-MASTER-2026" class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm font-mono uppercase focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                @error('code')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Certificate Type --}}
            <div>
                <label for="certificate_type" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                    Certificate Category <span class="text-rose-500">*</span>
                </label>
                <select id="certificate_type" name="certificate_type" required class="w-full bg-slate-50 border border-slate-300 rounded-xl px-4 py-3 text-sm focus:outline-hidden focus:border-emerald-500 focus:bg-white transition">
                    <option value="training_completion" {{ old('certificate_type') === 'training_completion' ? 'selected' : '' }}>Training Completion Certificate</option>
                    <option value="membership" {{ old('certificate_type') === 'membership' ? 'selected' : '' }}>Official Membership Certificate</option>
                    <option value="professional_certification" {{ old('certificate_type') === 'professional_certification' ? 'selected' : '' }}>Professional EV Certification</option>
                    <option value="participation" {{ old('certificate_type') === 'participation' ? 'selected' : '' }}>Participation Certificate</option>
                    <option value="recognition_appreciation" {{ old('certificate_type') === 'recognition_appreciation' ? 'selected' : '' }}>Recognition & Appreciation</option>
                    <option value="other" {{ old('certificate_type') === 'other' ? 'selected' : '' }}>Other Custom Certificate</option>
                </select>
                @error('certificate_type')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Orientation --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Orientation <span class="text-rose-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <label class="flex items-center gap-3 p-3.5 border rounded-2xl cursor-pointer transition hover:bg-slate-50 has-checked:border-emerald-500 has-checked:bg-emerald-50/50">
                        <input type="radio" name="orientation" value="landscape" {{ old('orientation', 'landscape') === 'landscape' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <div class="text-xs font-bold text-slate-800"><i class="fa-solid fa-image text-slate-400 mr-1"></i> Landscape</div>
                            <div class="text-[10px] text-slate-500">Standard A4 (Horizontal)</div>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-3.5 border rounded-2xl cursor-pointer transition hover:bg-slate-50 has-checked:border-emerald-500 has-checked:bg-emerald-50/50">
                        <input type="radio" name="orientation" value="portrait" {{ old('orientation') === 'portrait' ? 'checked' : '' }} class="text-emerald-600 focus:ring-emerald-500">
                        <div>
                            <div class="text-xs font-bold text-slate-800"><i class="fa-solid fa-file text-slate-400 mr-1"></i> Portrait</div>
                            <div class="text-[10px] text-slate-500">Vertical A4</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Background Image Upload Dropzone --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Certificate Background Artwork (PNG / JPG)
                </label>
                <div class="relative border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-3xl p-6 sm:p-8 text-center bg-slate-50/50 hover:bg-emerald-50/30 transition group">
                    <input type="file" id="background_image" name="background_image" accept="image/png,image/jpeg,image/jpg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewTemplateImage(this)">
                    
                    <div id="uploadPlaceholder" class="space-y-3">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-2xl mx-auto shadow-xs group-hover:scale-110 transition">
                            <i class="fa-solid fa-cloud-arrow-up"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Click or drag & drop high-resolution certificate artwork</p>
                            <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 10MB (Recommended size: 3508 x 2480 px for 300 DPI A4)</p>
                        </div>
                    </div>

                    <div id="imagePreviewContainer" class="hidden space-y-3">
                        <img id="imagePreview" src="" alt="Template Artwork Preview" class="max-h-64 mx-auto rounded-xl shadow-md border border-slate-200">
                        <p id="imageFileInfo" class="text-xs font-bold text-emerald-700"></p>
                        <span class="inline-block text-[11px] text-slate-500 bg-white px-3 py-1 rounded-full border border-slate-200">Click to choose a different image</span>
                    </div>
                </div>
                @error('background_image')
                    <p class="text-xs text-rose-500 mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="p-4 bg-emerald-50/60 rounded-2xl border border-emerald-200/60 flex items-start gap-3">
            <i class="fa-solid fa-wand-magic-sparkles text-emerald-600 mt-0.5"></i>
            <div class="text-xs text-emerald-900 leading-relaxed">
                <strong>Smart Coordinate Detection:</strong> After saving, the system will automatically place standard coordinate boxes for Recipient Name, Title, Certificate Number, Issue Date, Signatory, and the <strong>QR Code</strong>. You can then interactively drag or adjust each element on the live visual canvas.
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.certificates.templates') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs px-6 py-2.5 rounded-xl shadow-xs transition">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Save & Open Visual Layout Editor &rarr;</span>
            </button>
        </div>
    </form>
</div>

<script>
function previewTemplateImage(input) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
            document.getElementById('uploadPlaceholder').classList.add('hidden');
            document.getElementById('imageFileInfo').textContent = `${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection
