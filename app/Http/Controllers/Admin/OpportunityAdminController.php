<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\OpportunityApplication;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OpportunityAdminController extends Controller
{
    public function index()
    {
        $opportunities = Opportunity::with(['category', 'applications'])->latest()->paginate(10);
        $categories = OpportunityCategory::all();

        return view('admin.opportunities.index', compact('opportunities', 'categories'));
    }

    public function create()
    {
        $categories = OpportunityCategory::all();
        return view('admin.opportunities.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:opportunity_categories,id',
            'title' => 'required|string|max:255',
            'provider_name' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'description' => 'required|string',
            'eligibility_criteria' => 'required|string',
            'location' => 'required|string|max:100',
            'deadline' => 'nullable|date',
            'application_type' => 'required|in:internal,external',
            'external_url' => 'nullable|required_if:application_type,external|url',
            'status' => 'required|in:draft,published,closed,archived',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_verified'] = true;
        $validated['published_by'] = Auth::id();
        $validated['published_at'] = ($validated['status'] === 'published') ? now() : null;

        $opportunity = Opportunity::create($validated);
        AuditLog::log('created_opportunity', 'opportunity', (string)$opportunity->id, null, ['title' => $opportunity->title]);

        return redirect()->route('admin.opportunities.index')->with('success', 'Opportunity published successfully.');
    }

    public function show(int $id)
    {
        $opportunity = Opportunity::with(['category', 'applications.member.category'])->findOrFail($id);
        return view('admin.opportunities.show', compact('opportunity'));
    }

    public function updateApplicationStatus(Request $request, int $appId)
    {
        $request->validate([
            'status' => 'required|in:submitted,under_review,shortlisted,accepted,rejected',
            'feedback' => 'nullable|string|max:1000',
        ]);

        $app = OpportunityApplication::with(['member', 'opportunity'])->findOrFail($appId);
        $oldStatus = $app->status;

        $app->update([
            'status' => $request->status,
            'admin_feedback' => $request->feedback,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        AuditLog::log('updated_opportunity_app_status', 'opportunity', (string)$app->id, ['status' => $oldStatus], ['status' => $request->status]);

        NotificationCustom::send(
            $app->member->user_id,
            "Opportunity Application Update ({$app->opportunity->title})",
            "Your application {$app->application_number} status is now: " . strtoupper($request->status) . ($request->feedback ? " - Feedback: {$request->feedback}" : ""),
            route('portal.opportunities'),
            'info'
        );

        return back()->with('success', "Application status updated to {$request->status}.");
    }
}
