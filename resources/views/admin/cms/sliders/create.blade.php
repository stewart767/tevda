@extends('layouts.admin')

@section('title', 'Upload New Homepage Slide — TEVDA Admin')
@section('page_title', 'Upload Homepage Slide')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cms.sliders.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-900 font-heading">Upload New Slide</h2>
                <p class="text-xs text-slate-500">Add a new panoramic hero image to the homepage slider.</p>
            </div>
        </div>

        <a href="{{ route('admin.cms.sliders.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
            Cancel
        </a>
    </div>

    <!-- Upload Form -->
    <form method="POST" action="{{ route('admin.cms.sliders.store') }}" enctype="multipart/form-data" class="space-y-6"
          x-data="{
              photoPreview: null,
              handleFile(e) {
                  const file = e.target.files[0];
                  if (file) {
                      const reader = new FileReader();
                      reader.onload = (event) => {
                          this.photoPreview = event.target.result;
                      };
                      reader.readAsDataURL(file);
                  }
              }
          }">
        @csrf

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            
            <!-- Image Upload Dropzone / Box with Live Preview -->
            <div>
                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                    Slide Photograph <span class="text-rose-500">*</span>
                </label>
                
                <div class="relative border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-6 text-center transition bg-slate-50/50 hover:bg-emerald-50/20 group">
                    <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp" required
                           @change="handleFile($event)"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">

                    <!-- Live Image Preview -->
                    <template x-if="photoPreview">
                        <div class="space-y-3">
                            <div class="relative h-60 sm:h-72 w-full rounded-xl overflow-hidden bg-slate-950 shadow-inner">
                                <img :src="photoPreview" alt="Selected Preview" class="w-full h-full object-cover">
                                <div class="absolute top-3 right-3 bg-slate-900/80 text-white text-[11px] font-bold px-3 py-1 rounded-full backdrop-blur-md">
                                    Click to change image
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Default Empty State Placeholder -->
                    <template x-if="!photoPreview">
                        <div class="py-8 space-y-3">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto transition group-hover:scale-110">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">Drag & drop your slider photo here, or <span class="text-emerald-600 underline">browse</span></p>
                                <p class="text-xs text-slate-400 mt-1">Recommended: 1920 &times; 800 px (Panoramic JPG, PNG, WebP &bull; Max 10MB)</p>
                            </div>
                        </div>
                    </template>
                </div>
                @error('image')
                    <p class="text-xs text-rose-600 mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slide Title & Subtitle (Optional metadata) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Slide Title / Alt Text <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Commercial EV Bajaji & Solar Hub"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">
                    <p class="text-[11px] text-slate-400 mt-1">Describes the photo for accessibility and CMS management.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Display Order Number</label>
                    <input type="number" name="order_number" value="{{ old('order_number', $nextOrder) }}" min="1"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">
                    <p class="text-[11px] text-slate-400 mt-1">Order in carousel (1 = First slide, 2 = Second, etc.).</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Slide Caption / Subtitle <span class="text-slate-400 font-normal">(Optional)</span></label>
                <textarea name="subtitle" rows="2" placeholder="e.g. Empowering Tanzanian commercial drivers with clean battery swapping infrastructure..."
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">{{ old('subtitle') }}</textarea>
            </div>

            <!-- Link URL (Optional) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Optional Action Link URL <span class="text-slate-400 font-normal">(Optional)</span></label>
                <input type="url" name="link_url" value="{{ old('link_url') }}" placeholder="https://... or /programmes"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">
            </div>

            <!-- Active Status Toggle -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="block text-xs font-bold text-slate-900">Make Slide Live Immediately</span>
                    <span class="block text-[11px] text-slate-500">When enabled, this photo will immediately appear in the homepage slider.</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-hidden rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                </label>
            </div>

        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.cms.sliders.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center gap-2 cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                <span>Save & Publish Slide</span>
            </button>
        </div>
    </form>

</div>
@endsection
