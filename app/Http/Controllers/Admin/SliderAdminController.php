<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\AuditLog;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderAdminController extends Controller
{
    /**
     * Display a listing of homepage sliders.
     */
    public function index()
    {
        $sliders = Slider::ordered()->get();
        $totalCount = $sliders->count();
        $activeCount = $sliders->where('is_active', true)->count();

        return view('admin.cms.sliders.index', compact('sliders', 'totalCount', 'activeCount'));
    }

    /**
     * Show the form for creating a new slider.
     */
    public function create()
    {
        $nextOrder = (Slider::max('order_number') ?? 0) + 1;
        return view('admin.cms.sliders.create', compact('nextOrder'));
    }

    /**
     * Store a newly created slider in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'link_url' => 'nullable|string|max:255',
            'order_number' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $imagePath = DocumentService::storePublic($request->file('image'), 'sliders');

        $slider = Slider::create([
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'image_path' => $imagePath,
            'order_number' => $request->input('order_number', (Slider::max('order_number') ?? 0) + 1),
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::log('created_slider_slide', 'cms', (string)$slider->id, null, [
            'title' => $slider->title,
            'image_path' => $slider->image_path,
        ]);

        return redirect()->route('admin.cms.sliders.index')->with('success', 'New homepage slider photo uploaded successfully.');
    }

    /**
     * Show the form for editing the specified slider.
     */
    public function edit(int $id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.cms.sliders.edit', compact('slider'));
    }

    /**
     * Update the specified slider in storage.
     */
    public function update(Request $request, int $id)
    {
        $slider = Slider::findOrFail($id);

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'link_url' => 'nullable|string|max:255',
            'order_number' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $updateData = [
            'title' => $validated['title'] ?? null,
            'subtitle' => $validated['subtitle'] ?? null,
            'link_url' => $validated['link_url'] ?? null,
            'order_number' => $request->input('order_number', $slider->order_number),
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->hasFile('image')) {
            // Delete old uploaded image if it was stored in public storage disk
            if (!str_starts_with($slider->image_path, 'images/') && Storage::disk('public')->exists($slider->image_path)) {
                Storage::disk('public')->delete($slider->image_path);
            }

            $updateData['image_path'] = DocumentService::storePublic($request->file('image'), 'sliders');
        }

        $slider->update($updateData);

        AuditLog::log('updated_slider_slide', 'cms', (string)$slider->id, null, [
            'title' => $slider->title,
            'is_active' => $slider->is_active,
        ]);

        return redirect()->route('admin.cms.sliders.index')->with('success', 'Homepage slider updated successfully.');
    }

    /**
     * Toggle active status of the specified slider.
     */
    public function toggleStatus(int $id)
    {
        $slider = Slider::findOrFail($id);
        $slider->is_active = !$slider->is_active;
        $slider->save();

        AuditLog::log('toggled_slider_status', 'cms', (string)$slider->id, null, [
            'is_active' => $slider->is_active,
        ]);

        $statusText = $slider->is_active ? 'activated and is now visible on the homepage' : 'deactivated and is now hidden';
        return back()->with('success', "Slide #{$slider->id} was {$statusText}.");
    }

    /**
     * Remove the specified slider from storage.
     */
    public function destroy(int $id)
    {
        $slider = Slider::findOrFail($id);

        // Delete custom uploaded image file if exists in storage
        if (!str_starts_with($slider->image_path, 'images/') && Storage::disk('public')->exists($slider->image_path)) {
            Storage::disk('public')->delete($slider->image_path);
        }

        $sliderTitle = $slider->title ?? "Slide #{$slider->id}";
        $slider->delete();

        AuditLog::log('deleted_slider_slide', 'cms', (string)$id, null, [
            'title' => $sliderTitle,
        ]);

        return redirect()->route('admin.cms.sliders.index')->with('success', "{$sliderTitle} was deleted successfully.");
    }
}
