<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Member;
use App\Models\TrainingEnrolment;
use App\Models\Certificate;
use App\Models\Payment;
use App\Models\OpportunityApplication;
use App\Models\ProjectApplication;
use Illuminate\Http\Request;

class AuditReportController extends Controller
{
    public function auditLogs(Request $request)
    {
        $query = AuditLog::with('user');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('action')) {
            $query->where('action', 'like', "%{$request->action}%");
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhere('record_id', 'like', "%{$search}%");
            });
        }

        $logs = $query->latest()->paginate(20);

        return view('admin.reports.audit_logs', compact('logs'));
    }

    public function reportsIndex()
    {
        $membershipStats = [
            'total' => Member::count(),
            'approved' => Member::where('status', 'approved')->count(),
            'pending' => Member::whereIn('status', ['submitted', 'payment_pending', 'under_review'])->count(),
            'rejected' => Member::where('status', 'rejected')->count(),
        ];

        $financialStats = [
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'pending_amount' => Payment::where('status', 'pending')->sum('amount'),
            'completed_count' => Payment::where('status', 'completed')->count(),
        ];

        $trainingStats = [
            'enrolments' => TrainingEnrolment::count(),
            'certificates_issued' => Certificate::where('certificate_type', 'training_completion')->count(),
        ];

        $opportunityStats = [
            'applications' => OpportunityApplication::count(),
        ];

        $projectStats = [
            'applications' => ProjectApplication::count(),
        ];

        return view('admin.reports.index', compact(
            'membershipStats',
            'financialStats',
            'trainingStats',
            'opportunityStats',
            'projectStats'
        ));
    }
}
