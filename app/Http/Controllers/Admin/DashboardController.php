<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipCategory;
use App\Models\TrainingEnrolment;
use App\Models\Certificate;
use App\Models\Opportunity;
use App\Models\OpportunityApplication;
use App\Models\Project;
use App\Models\Beneficiary;
use App\Models\Partner;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Complaint;
use App\Models\AuditLog;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. KPI Stats
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::where('status', 'approved')->count(),
            'pending_applications' => Member::whereIn('status', ['submitted', 'payment_pending', 'payment_confirmed', 'under_review'])->count(),
            'training_enrolments' => TrainingEnrolment::count(),
            'certificates_issued' => Certificate::where('status', 'valid')->count(),
            'active_opportunities' => Opportunity::where('status', 'published')->count(),
            'opportunity_applications' => OpportunityApplication::count(),
            'total_projects' => Project::count(),
            'total_beneficiaries' => Beneficiary::count(),
            'total_partners' => Partner::count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
            'open_complaints' => Complaint::whereNotIn('status', ['resolved', 'closed'])->count(),
        ];

        // 2. Breakdown Charts
        $membersByCategory = MembershipCategory::withCount(['members' => function ($q) {
            $q->where('status', 'approved');
        }])->get();

        $membersByRegion = Region::withCount(['members' => function ($q) {
            $q->where('status', 'approved');
        }])->having('members_count', '>', 0)->orderByDesc('members_count')->take(6)->get();

        $recentApplications = Member::with(['category', 'region'])
            ->latest()
            ->take(6)
            ->get();

        $recentPayments = Payment::with(['member', 'invoice'])
            ->latest()
            ->take(5)
            ->get();

        $recentAuditLogs = AuditLog::latest()->take(6)->get();

        return view('admin.dashboard', compact(
            'stats',
            'membersByCategory',
            'membersByRegion',
            'recentApplications',
            'recentPayments',
            'recentAuditLogs'
        ));
    }
}
