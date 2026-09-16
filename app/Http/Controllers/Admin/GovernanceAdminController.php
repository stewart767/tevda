<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leader;
use App\Models\GovernanceBody;
use App\Models\Committee;
use App\Models\Zone;
use App\Models\Region;
use App\Models\District;
use App\Models\Branch;
use App\Models\Partner;
use App\Models\PartnershipEnquiry;
use App\Models\AuditLog;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GovernanceAdminController extends Controller
{
    public function index()
    {
        $leaders = Leader::with('governanceBody')->orderBy('is_founding_leader', 'desc')->orderBy('order_number')->get();
        $bodies = GovernanceBody::withCount('leaders')->orderBy('order_number')->get();
        $committees = Committee::withCount('members')->get();
        $branches = Branch::with('region')->get();
        $partners = Partner::orderBy('order_number')->get();
        $enquiries = PartnershipEnquiry::latest()->take(10)->get();

        return view('admin.governance.index', compact(
            'leaders',
            'bodies',
            'committees',
            'branches',
            'partners',
            'enquiries'
        ));
    }

    public function createLeader()
    {
        $bodies = GovernanceBody::all();
        return view('admin.governance.leader_create', compact('bodies'));
    }

    public function storeLeader(Request $request)
    {
        $validated = $request->validate([
            'governance_body_id' => 'nullable|exists:governance_bodies,id',
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'biography' => 'nullable|string',
            'qualifications' => 'nullable|string',
            'sector_experience' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'official_office_contact' => 'nullable|string|max:255',
            'term_period' => 'nullable|string|max:100',
            'is_founding_leader' => 'nullable|boolean',
            'founding_position' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',
            'order_number' => 'nullable|integer',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = DocumentService::storePublic($request->file('photo'), 'leaders');
        }

        $validated['is_founding_leader'] = $request->boolean('is_founding_leader');
        $validated['is_active'] = true;

        $leader = Leader::create($validated);
        AuditLog::log('created_leader', 'governance', (string)$leader->id, null, ['name' => $leader->name, 'position' => $leader->position]);

        return redirect()->route('admin.governance.index')->with('success', 'Leadership profile added successfully.');
    }

    public function storeBranch(Request $request)
    {
        $validated = $request->validate([
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'nullable|exists:districts,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:branches,code',
            'location_description' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'status' => 'required|in:proposed,active,inactive',
        ]);

        $branch = Branch::create($validated);
        AuditLog::log('created_branch', 'governance', (string)$branch->id, null, ['code' => $branch->code]);

        return back()->with('success', "Branch {$branch->name} registered.");
    }

    public function storePartner(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'description' => 'nullable|string',
            'website_url' => 'nullable|url|max:255',
            'collaboration_area' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:3072',
            'order_number' => 'nullable|integer',
            'is_featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = \Illuminate\Support\Str::slug($validated['name']) . '-' . rand(100, 999);
        $validated['is_featured'] = $request->boolean('is_featured', true);
        $validated['status'] = 'active';

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = DocumentService::storePublic($request->file('logo'), 'partners');
        }

        $partner = Partner::create($validated);
        AuditLog::log('created_partner', 'governance', (string)$partner->id, null, ['name' => $partner->name]);

        return back()->with('success', "Partner {$partner->name} registered successfully.");
    }
}
