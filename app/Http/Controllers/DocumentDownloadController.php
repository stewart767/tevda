<?php

namespace App\Http\Controllers;

use App\Models\MembershipDocument;
use App\Models\Complaint;
use App\Models\OpportunityApplication;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentDownloadController extends Controller
{
    /**
     * Download a private membership document (NIDA, Driving Licence, etc.)
     * Access allowed only to:
     * - The document's owner (Member)
     * - Authorized TEVDA staff (Super Admin, Membership Officer, Auditor)
     */
    public function downloadMembershipDocument(int $id)
    {
        if (!Auth::check()) {
            abort(401, 'Please log in to access this document.');
        }

        $doc = MembershipDocument::with('member')->findOrFail($id);
        $user = Auth::user();

        $isOwner = ($user->member && $user->member->id === $doc->member_id);
        $isStaff = $user->isStaff() && ($user->hasPermission('members.view') || $user->isSuperAdmin());

        if (!$isOwner && !$isStaff) {
            AuditLog::log('unauthorized_doc_access_attempt', 'security', (string)$doc->id);
            abort(403, 'Unauthorized access to confidential document.');
        }

        if (!Storage::disk('local')->exists($doc->file_path)) {
            abort(404, 'File not found on secure storage.');
        }

        AuditLog::log('downloaded_private_doc', 'membership', (string)$doc->id, null, ['file' => $doc->file_name]);

        return Storage::disk('local')->download($doc->file_path, $doc->file_name);
    }

    /**
     * Download complaint evidence file.
     */
    public function downloadComplaintEvidence(int $id)
    {
        if (!Auth::check()) {
            abort(401);
        }

        $complaint = Complaint::findOrFail($id);
        $user = Auth::user();

        if (!$user->isStaff() || (!$user->hasPermission('complaints.manage') && !$user->isSuperAdmin())) {
            abort(403, 'Unauthorized access to confidential complaint files.');
        }

        if (!$complaint->evidence_file_path || !Storage::disk('local')->exists($complaint->evidence_file_path)) {
            abort(404, 'Evidence file not found.');
        }

        AuditLog::log('downloaded_complaint_evidence', 'complaints', (string)$complaint->id);

        return Storage::disk('local')->download($complaint->evidence_file_path);
    }
}
