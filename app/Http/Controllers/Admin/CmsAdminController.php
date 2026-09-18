<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\Event;
use App\Models\Resource;
use App\Models\ContactMessage;
use App\Models\Complaint;
use App\Models\Faq;
use App\Models\Setting;
use App\Models\AuditLog;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CmsAdminController extends Controller
{
    public function newsIndex()
    {
        $news = News::latest()->paginate(15);
        return view('admin.cms.news_index', compact('news'));
    }

    public function createNews()
    {
        return view('admin.cms.news_create');
    }

    public function storeNews(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:announcements,training,opportunities,events,articles,media_statements',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'author_name' => 'nullable|string|max:100',
            'publication_date' => 'required|date',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['is_published'] = $request->boolean('is_published', true);

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = DocumentService::storePublic($request->file('featured_image'), 'news');
        }

        $news = News::create($validated);
        AuditLog::log('created_news', 'cms', (string)$news->id, null, ['title' => $news->title]);

        return redirect()->route('admin.cms.news.index')->with('success', 'News article published.');
    }

    public function resourcesIndex()
    {
        $resources = Resource::latest()->paginate(15);
        return view('admin.cms.resources_index', compact('resources'));
    }

    public function storeResource(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:constitution_and_governance,membership,training,projects,policies,reports,forms',
            'version' => 'nullable|string|max:20',
            'effective_date' => 'nullable|date',
            'approving_authority' => 'nullable|string|max:255',
            'visibility' => 'required|in:public,members_only',
            'file' => 'required|file|mimes:pdf,doc,docx,xlsx,zip|max:20480',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['file_path'] = DocumentService::storePublic($request->file('file'), 'resources');
        $validated['file_size'] = $request->file('file')->getSize();
        $validated['file_type'] = $request->file('file')->getClientOriginalExtension();
        $validated['is_published'] = true;

        $res = Resource::create($validated);
        AuditLog::log('created_resource', 'cms', (string)$res->id, null, ['title' => $res->title]);

        return back()->with('success', 'Resource document uploaded.');
    }

    public function contactsIndex()
    {
        $messages = ContactMessage::latest()->paginate(15);
        return view('admin.cms.contacts_index', compact('messages'));
    }

    public function updateContactStatus(Request $request, int $id)
    {
        $msg = ContactMessage::findOrFail($id);
        $msg->update([
            'status' => $request->status,
            'reply_notes' => $request->reply_notes,
            'handled_by' => Auth::id(),
            'handled_at' => now(),
        ]);

        return back()->with('success', 'Contact message updated.');
    }

    public function complaintsIndex()
    {
        $complaints = Complaint::with('assignedOfficer')->latest()->paginate(15);
        return view('admin.cms.complaints_index', compact('complaints'));
    }

    public function showComplaint(int $id)
    {
        $complaint = Complaint::with('assignedOfficer')->findOrFail($id);
        return view('admin.cms.complaint_show', compact('complaint'));
    }

    public function updateComplaint(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,assigned,investigation,resolved,closed',
            'internal_notes' => 'nullable|string',
            'resolution_summary' => 'nullable|string',
        ]);

        $complaint = Complaint::findOrFail($id);
        $oldStatus = $complaint->status;

        $complaint->update([
            'status' => $request->status,
            'internal_notes' => $request->internal_notes,
            'resolution_summary' => $request->resolution_summary,
            'closed_at' => in_array($request->status, ['resolved', 'closed']) ? now() : null,
        ]);

        AuditLog::log('updated_complaint_status', 'complaints', (string)$complaint->id, ['status' => $oldStatus], ['status' => $request->status]);

        return back()->with('success', "Complaint {$complaint->complaint_number} updated to {$request->status}.");
    }

    public function settingsIndex()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.cms.settings_index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'site_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
            'chairman_photo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
            'chairman_signature' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:5120',
        ]);

        // Handle Site Logo
        if ($request->boolean('remove_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('site_logo', null, 'site', 'file', 'Official Association Logo');
        } elseif ($request->hasFile('site_logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $path = DocumentService::storePublic($request->file('site_logo'), 'branding');
            Setting::set('site_logo', $path, 'site', 'file', 'Official Association Logo');
        }

        // Handle Chairman Portrait Photo
        if ($request->boolean('remove_chairman_photo')) {
            $oldPhoto = Setting::get('chairman_photo');
            if ($oldPhoto && !str_contains($oldPhoto, 'chairman_dr_charles_mwansasu') && Storage::disk('public')->exists($oldPhoto)) {
                Storage::disk('public')->delete($oldPhoto);
            }
            Setting::set('chairman_photo', 'images/chairman_dr_charles_mwansasu.jpg', 'about', 'file', 'Chairman Portrait Photo');
        } elseif ($request->hasFile('chairman_photo')) {
            $oldPhoto = Setting::get('chairman_photo');
            if ($oldPhoto && !str_contains($oldPhoto, 'chairman_dr_charles_mwansasu') && Storage::disk('public')->exists($oldPhoto)) {
                Storage::disk('public')->delete($oldPhoto);
            }

            $path = DocumentService::storePublic($request->file('chairman_photo'), 'branding');
            Setting::set('chairman_photo', $path, 'about', 'file', 'Chairman Portrait Photo');
        }

        // Handle Chairman Official Signature
        if ($request->boolean('remove_chairman_signature')) {
            $oldSig = Setting::get('chairman_signature');
            if ($oldSig && Storage::disk('public')->exists($oldSig)) {
                Storage::disk('public')->delete($oldSig);
            }
            Setting::set('chairman_signature', null, 'site', 'file', 'Official Chairman Signature');
        } elseif ($request->hasFile('chairman_signature')) {
            $oldSig = Setting::get('chairman_signature');
            if ($oldSig && Storage::disk('public')->exists($oldSig)) {
                Storage::disk('public')->delete($oldSig);
            }

            $path = DocumentService::storePublic($request->file('chairman_signature'), 'branding');
            Setting::set('chairman_signature', $path, 'site', 'file', 'Official Chairman Signature');
        }

        $data = $request->except([
            '_token', 
            'site_logo', 
            'remove_logo', 
            'chairman_photo', 
            'remove_chairman_photo',
            'chairman_signature',
            'remove_chairman_signature'
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        AuditLog::log('updated_site_settings', 'settings');

        return back()->with('success', 'Platform settings, executive welcome message, and brand assets updated successfully.');
    }
}
