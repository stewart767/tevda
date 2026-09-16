<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectCategory;
use App\Models\ProjectApplication;
use App\Models\Beneficiary;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProjectAdminController extends Controller
{
    public function index()
    {
        $projects = Project::with(['category', 'applications', 'beneficiaries'])->latest()->paginate(10);
        $totalBeneficiaries = Beneficiary::count();

        return view('admin.projects.index', compact('projects', 'totalBeneficiaries'));
    }

    public function create()
    {
        $categories = ProjectCategory::all();
        return view('admin.projects.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:project_categories,id',
            'title' => 'required|string|max:255',
            'summary' => 'required|string|max:500',
            'description' => 'required|string',
            'location' => 'required|string|max:100',
            'target_beneficiaries_count' => 'required|integer|min:1',
            'funding_status' => 'required|in:proposal_under_development,seeking_partners,funding_approved,partially_funded,to_be_confirmed',
            'project_status' => 'required|in:proposal_under_development,open_for_applications,applications_closed,shortlisting_in_progress,active,completed,paused',
            'budget_amount' => 'nullable|numeric|min:0',
            'application_open_date' => 'nullable|date',
            'application_close_date' => 'nullable|date',
            'partner_organisations' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = DocumentService::storePublic($request->file('featured_image'), 'projects');
        }

        $project = Project::create($validated);
        AuditLog::log('created_project', 'project', (string)$project->id, null, ['title' => $project->title]);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show(int $id)
    {
        $project = Project::with([
            'category',
            'applications.member.category',
            'beneficiaries.member.category'
        ])->findOrFail($id);

        return view('admin.projects.show', compact('project'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'project_status' => 'required|in:proposal_under_development,open_for_applications,applications_closed,shortlisting_in_progress,active,completed,paused',
            'funding_status' => 'required|in:proposal_under_development,seeking_partners,funding_approved,partially_funded,to_be_confirmed',
        ]);

        $project = Project::findOrFail($id);
        $oldStatus = $project->project_status;

        $project->update([
            'project_status' => $request->project_status,
            'funding_status' => $request->funding_status,
        ]);

        AuditLog::log('updated_project_status', 'project', (string)$project->id, ['status' => $oldStatus], ['status' => $request->project_status, 'funding' => $request->funding_status]);

        return back()->with('success', 'Project status updated successfully.');
    }

    public function shortlistApplication(Request $request, int $appId)
    {
        $app = ProjectApplication::with('member')->findOrFail($appId);
        $app->update([
            'status' => 'shortlisted',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'reviewer_notes' => $request->input('notes', 'Shortlisted for project evaluation.'),
        ]);

        AuditLog::log('shortlisted_project_applicant', 'project', (string)$app->id, null, ['member' => $app->member->full_name]);

        NotificationCustom::send(
            $app->member->user_id,
            'Project Application Shortlisted',
            "Congratulations! You have been shortlisted for {$app->project->title}.",
            route('portal.projects'),
            'success'
        );

        return back()->with('success', "Applicant {$app->member->full_name} has been shortlisted.");
    }

    public function allocateBeneficiary(Request $request, int $appId)
    {
        $request->validate([
            'asset_type' => 'required|string|max:100',
            'asset_registration_or_serial' => 'required|string|max:100',
            'allocation_date' => 'required|date',
            'training_completed' => 'nullable|boolean',
        ]);

        $app = ProjectApplication::with(['member', 'project'])->findOrFail($appId);

        DB::beginTransaction();
        try {
            $app->update([
                'status' => 'asset_allocated',
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            $beneficiary = Beneficiary::updateOrCreate(
                ['project_id' => $app->project_id, 'member_id' => $app->member_id],
                [
                    'application_id' => $app->id,
                    'beneficiary_code' => Beneficiary::generateBeneficiaryCode(),
                    'asset_type' => $request->asset_type,
                    'asset_registration_or_serial' => $request->asset_registration_or_serial,
                    'allocation_date' => $request->allocation_date,
                    'training_completed' => $request->boolean('training_completed'),
                    'status' => 'active',
                    'monitoring_notes' => $request->input('notes', 'Asset allocated under official association deployment.'),
                ]
            );

            AuditLog::log('allocated_beneficiary_asset', 'project', (string)$beneficiary->id, null, [
                'beneficiary_code' => $beneficiary->beneficiary_code,
                'asset' => $beneficiary->asset_registration_or_serial,
            ]);

            NotificationCustom::send(
                $app->member->user_id,
                'Beneficiary Asset Allocation Approved!',
                "Your asset allocation for {$app->project->title} is confirmed. Beneficiary Code: {$beneficiary->beneficiary_code}. Asset: {$beneficiary->asset_registration_or_serial}.",
                route('portal.projects'),
                'success'
            );

            DB::commit();

            return back()->with('success', "Beneficiary {$app->member->full_name} allocated asset {$beneficiary->asset_registration_or_serial}! Code: {$beneficiary->beneficiary_code}");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Allocation failed: ' . $e->getMessage()]);
        }
    }
}
