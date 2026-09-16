<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\IdCardTemplate;
use App\Models\Member;
use App\Models\Setting;
use App\Services\PdfService;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdCardTemplateController extends Controller
{
    /**
     * List all ID Card templates.
     */
    public function index()
    {
        $templates = IdCardTemplate::withCount('cards')
            ->latest()
            ->get();

        $totalTemplates = $templates->count();
        $activeTemplates = $templates->where('is_active', true)->count();
        $defaultTemplate = $templates->where('is_default', true)->first();

        return view('admin.cards.templates.index', compact(
            'templates',
            'totalTemplates',
            'activeTemplates',
            'defaultTemplate'
        ));
    }

    /**
     * Show create/upload form for new ID card template.
     */
    public function create()
    {
        $presets = IdCardTemplate::getPresetProfiles();
        return view('admin.cards.templates.create', compact('presets'));
    }

    /**
     * Store new ID card template and uploaded backgrounds.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:100|unique:id_card_templates,code',
            'card_type' => 'required|string|in:standard,commercial,executive,membership',
            'front_background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'back_background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'preset' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        $frontPath = null;
        if ($request->hasFile('front_background_image')) {
            $file = $request->file('front_background_image');
            $filename = 'card_front_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $frontPath = $file->storeAs('id_card_templates', $filename, 'public');
        }

        $backPath = null;
        if ($request->hasFile('back_background_image')) {
            $file = $request->file('back_background_image');
            $filename = 'card_back_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $backPath = $file->storeAs('id_card_templates', $filename, 'public');
        }

        // Determine initial placeholders config based on preset or default
        $presets = IdCardTemplate::getPresetProfiles();
        $selectedPreset = $request->input('preset', 'official_standard');
        $placeholdersConfig = isset($presets[$selectedPreset]) 
            ? $presets[$selectedPreset]['config'] 
            : IdCardTemplate::getDefaultPlaceholdersConfig();

        $isDefault = $request->boolean('is_default');
        if ($isDefault || IdCardTemplate::count() === 0) {
            IdCardTemplate::query()->update(['is_default' => false]);
            $isDefault = true;
        }

        $template = IdCardTemplate::create([
            'name' => $validated['name'],
            'code' => Str::slug($validated['code'], '_'),
            'card_type' => $validated['card_type'],
            'front_background_image_path' => $frontPath,
            'back_background_image_path' => $backPath,
            'orientation' => 'landscape',
            'placeholders_config' => $placeholdersConfig,
            'is_active' => $request->boolean('is_active', true),
            'is_default' => $isDefault,
        ]);

        AuditLog::log('created_id_card_template', 'id_cards', (string)$template->id, null, ['name' => $template->name]);

        return redirect()->route('admin.cards.templates.edit', $template->id)
            ->with('success', "Template '{$template->name}' created successfully! You can now adjust field positions in the Interactive Studio.");
    }

    /**
     * Interactive Studio for adjusting template coordinates and design.
     */
    public function edit(int $id)
    {
        $template = IdCardTemplate::findOrFail($id);

        // Fetch a real sample member (Stewart Amri or first available)
        $sampleMember = Member::with(['category', 'region', 'district'])
            ->where('membership_number', 'TEVDA-2026-00019')
            ->first() ?? Member::with(['category', 'region', 'district'])->first();

        // Fallback sample object if no members exist in DB
        if (!$sampleMember) {
            $sampleMember = new Member([
                'full_name' => 'Stewart Amri',
                'membership_number' => 'TEVDA-2026-00019',
                'phone' => '+255 700 000 000',
                'email' => 'member@tevda.or.tz',
                'expiry_date' => now()->addYear(),
            ]);
            $sampleMember->setRelation('category', new \App\Models\MembershipCategory(['name' => 'Full Member']));
            $sampleMember->setRelation('region', new \App\Models\Region(['name' => 'Dar es Salaam']));
        }

        $verifyUrl = url('/verify/membership/' . ($sampleMember->membership_number ?? 'TEVDA-2026-00019'));
        $qrCodeUri = QrCodeService::dataUri($verifyUrl, 120);
        $logoUrl = Setting::getLogoUrl();

        $presets = IdCardTemplate::getPresetProfiles();

        return view('admin.cards.templates.edit', compact(
            'template',
            'sampleMember',
            'qrCodeUri',
            'logoUrl',
            'presets'
        ));
    }

    /**
     * Update template details, backgrounds, and placeholder coordinates.
     */
    public function update(Request $request, int $id)
    {
        $template = IdCardTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:100|unique:id_card_templates,code,' . $template->id,
            'card_type' => 'required|string|in:standard,commercial,executive,membership',
            'front_background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'back_background_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
            'placeholders_config' => 'nullable',
            'is_active' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        if ($request->hasFile('front_background_image')) {
            if ($template->front_background_image_path) {
                Storage::disk('public')->delete($template->front_background_image_path);
            }
            $file = $request->file('front_background_image');
            $filename = 'card_front_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $template->front_background_image_path = $file->storeAs('id_card_templates', $filename, 'public');
        }

        if ($request->hasFile('back_background_image')) {
            if ($template->back_background_image_path) {
                Storage::disk('public')->delete($template->back_background_image_path);
            }
            $file = $request->file('back_background_image');
            $filename = 'card_back_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $template->back_background_image_path = $file->storeAs('id_card_templates', $filename, 'public');
        }

        if ($request->filled('placeholders_config')) {
            $config = $request->input('placeholders_config');
            if (is_string($config)) {
                $config = json_decode($config, true);
            }
            if (is_array($config)) {
                $template->placeholders_config = $config;
            }
        }

        $template->name = $validated['name'];
        $template->code = Str::slug($validated['code'], '_');
        $template->card_type = $validated['card_type'];
        $template->is_active = $request->boolean('is_active');

        if ($request->boolean('is_default') && !$template->is_default) {
            IdCardTemplate::where('id', '!=', $template->id)->update(['is_default' => false]);
            $template->is_default = true;
        }

        $template->save();

        AuditLog::log('updated_id_card_template', 'id_cards', (string)$template->id, null, ['name' => $template->name]);

        return back()->with('success', 'ID Card Template and placeholder coordinates updated successfully!');
    }

    /**
     * Delete an ID card template.
     */
    public function destroy(int $id)
    {
        $template = IdCardTemplate::withCount('cards')->findOrFail($id);

        if ($template->cards_count > 0) {
            return back()->with('error', "Cannot delete template '{$template->name}' because {$template->cards_count} member ID cards are currently linked to it.");
        }

        if ($template->front_background_image_path) {
            Storage::disk('public')->delete($template->front_background_image_path);
        }
        if ($template->back_background_image_path) {
            Storage::disk('public')->delete($template->back_background_image_path);
        }

        $name = $template->name;
        $template->delete();

        AuditLog::log('deleted_id_card_template', 'id_cards', (string)$id, null, ['name' => $name]);

        return redirect()->route('admin.cards.templates')
            ->with('success', "Template '{$name}' deleted successfully.");
    }

    /**
     * Set a template as default.
     */
    public function setDefault(int $id)
    {
        IdCardTemplate::query()->update(['is_default' => false]);
        $template = IdCardTemplate::findOrFail($id);
        $template->update(['is_default' => true, 'is_active' => true]);

        return back()->with('success', "Template '{$template->name}' set as system default for all member ID cards.");
    }

    /**
     * Preview / download sample PDF for this template.
     */
    public function previewPdf(int $id)
    {
        $template = IdCardTemplate::findOrFail($id);
        $pdf = PdfService::generateSampleIdCardTemplatePdf($template);

        return $pdf->stream('TEVDA-Card-Template-Preview-' . $template->code . '.pdf');
    }
}
