@extends('layouts.admin')

@section('title', 'Edit Homepage Slide — TEVDA Admin')
@section('page_title', 'Edit Slide #' . $slider->id)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header Navigation -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.cms.sliders.index') }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-black text-slate-900 font-heading">Edit Slide #{{ $slider->id }}</h2>
                <p class="text-xs text-slate-500">Update photo, reorder, or modify metadata for this homepage slider.</p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.cms.sliders.destroy', $slider->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete this slide?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-3.5 py-2 bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs rounded-xl transition flex items-center gap-1.5 cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span>Delete</span>
                </button>
            </form>
            <a href="{{ route('admin.cms.sliders.index') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
                Back
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form method="POST" action="{{ route('admin.cms.sliders.update', $slider->id) }}" enctype="multipart/form-data" class="space-y-6"
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
        @method('PUT')

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/80 shadow-xs space-y-6">
            
            <!-- Current Photo & Replace Option -->
            <div>
                <label class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                    Slide Photograph
                </label>
                
                <div class="space-y-4">
                    <!-- Current / Selected Image Preview -->
                    <div class="relative h-60 sm:h-72 w-full rounded-2xl overflow-hidden bg-slate-950 shadow-md">
                        <img :src="photoPreview ? photoPreview : '{{ $slider->image_url }}'" 
                             alt="{{ $slider->title ?? 'Slide #'.$slider->id }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute top-3 left-3 bg-slate-950/80 text-white text-[11px] font-bold px-3 py-1 rounded-full backdrop-blur-md border border-white/20">
                            <span x-text="photoPreview ? 'New Photo Selected (Unsaved)' : 'Current Active Photo'"></span>
                        </div>
                    </div>

                    <!-- Change Image Upload Button -->
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl p-4 text-center transition bg-slate-50/50 hover:bg-emerald-50/20 group">
                        <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/webp"
                               @change="handleFile($event)"
                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                        <div class="flex items-center justify-center gap-2 text-xs font-bold text-slate-700 group-hover:text-emerald-700">
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Click here to replace with a new photograph</span>
                        </div>
                    </div>
                </div>
                @error('image')
                    <p class="text-xs text-rose-600 mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <!-- Slide Title & Ordering -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-2">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Slide Title / Alt Text <span class="text-slate-400 font-normal">(Optional)</span></label>
                    <input type="text" name="title" value="{{ old('title', $slider->title) }}" placeholder="e.g. Commercial EV Fleet"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Display Order Number</label>
                    <input type="number" name="order_number" value="{{ old('order_number', $slider->order_number) }}" min="1"
                           class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">
                    <p class="text-[11px] text-slate-400 mt-1">Lower numbers appear first in the carousel sequence.</p>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Slide Caption / Subtitle <span class="text-slate-400 font-normal">(Optional)</span></label>
                <textarea name="subtitle" rows="2" placeholder="e.g. Clean electric commercial transport..."
                          class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">{{ old('subtitle', $slider->subtitle) }}</textarea>
            </div>

            <!-- Link URL (Optional) -->
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Optional Action Link URL <span class="text-slate-400 font-normal">(Optional)</span></label>
                <input type="url" name="link_url" value="{{ old('link_url', $slider->link_url) }}" placeholder="https://... or /programmes"
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-xs text-slate-900 focus:bg-white focus:border-emerald-500 focus:outline-hidden transition">
            </div>

            <!-- Active Status Toggle -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                <div>
                    <span class="block text-xs font-bold text-slate-900">Active on Homepage</span>
                    <span class="block text-[11px] text-slate-500">Enable or disable this photo from appearing in the live hero carousel.</span>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $slider->is_active) ? 'checked' : '' }} class="sr-only peer">
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
                <span>Save Changes</span>
            </button>
        </div>
    </form>

</div>
@endsection
