<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MembershipCard;
use App\Models\Certificate;
use App\Models\TrainingProgramme;
use App\Models\TrainingSession;
use App\Models\TrainingEnrolment;
use App\Models\Opportunity;
use App\Models\OpportunityApplication;
use App\Models\Project;
use App\Models\ProjectApplication;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Complaint;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use App\Services\PdfService;
use App\Services\QrCodeService;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $member = Member::with(['category', 'region', 'district', 'card', 'vehicles', 'membershipCertificate'])
            ->where('user_id', $user->id)
            ->first();

        if (!$member) {
            return redirect()->route('membership.apply');
        }

        $enrolments = TrainingEnrolment::with(['session.course.programme', 'result', 'attendances'])
            ->where('member_id', $member->id)
            ->latest()
            ->take(5)
            ->get();

        $certificates = Certificate::where('member_id', $member->id)
            ->where('status', 'valid')
            ->latest()
            ->get();

        $opportunityApps = OpportunityApplication::with('opportunity.category')
            ->where('member_id', $member->id)
            ->latest()
            ->take(5)
            ->get();

        $invoices = Invoice::with('payments')
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        // Ensure all invoices have a control number
        foreach ($invoices as $inv) {
            if (empty($inv->control_number)) {
                $inv->update(['control_number' => Invoice::generateControlNumber()]);
            }
        }

        $latestInvoice = $invoices->first();

        $qrCodeUri = null;
        if ($member->membership_number) {
            $verifyUrl = url('/verify/membership/' . $member->membership_number);
            $qrCodeUri = QrCodeService::dataUri($verifyUrl, 160);
        }

        return view('portal.dashboard', compact(
            'member',
            'enrolments',
            'certificates',
            'opportunityApps',
            'invoices',
            'latestInvoice',
            'qrCodeUri'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        $member = Member::with(['category', 'region', 'district', 'vehicles', 'documents'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('portal.profile', compact('user', 'member'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $member = Member::with('primaryVehicle')->where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'physical_address' => 'required|string|max:255',
            'occupation' => 'nullable|string|max:100',
            'ev_sector' => 'nullable|string|max:100',
            'employer' => 'nullable|string|max:100',
            'next_of_kin_name' => 'nullable|string|max:100',
            'next_of_kin_relationship' => 'nullable|string|max:50',
            'next_of_kin_phone' => 'nullable|string|max:20',
            'passport_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:3072',

            // Vehicle updates
            'vehicle_type' => 'nullable|string|in:two_wheeler,three_wheeler,passenger_car,van,minibus,bus,commercial_truck,other',
            'vehicle_make' => 'nullable|string|max:50',
            'vehicle_model' => 'nullable|string|max:50',
            'vehicle_registration' => 'nullable|string|max:30',
            'charging_type' => 'nullable|string|in:ac_slow,dc_fast,battery_swap,dual,other',
        ]);

        $user->update(['phone' => $validated['phone']]);

        $member->update([
            'phone' => $validated['phone'],
            'physical_address' => $validated['physical_address'],
            'occupation' => $validated['occupation'] ?? $member->occupation,
            'ev_sector' => $validated['ev_sector'] ?? $member->ev_sector,
            'employer' => $validated['employer'] ?? $member->employer,
            'next_of_kin_name' => $validated['next_of_kin_name'] ?? $member->next_of_kin_name,
            'next_of_kin_relationship' => $validated['next_of_kin_relationship'] ?? $member->next_of_kin_relationship,
            'next_of_kin_phone' => $validated['next_of_kin_phone'] ?? $member->next_of_kin_phone,
        ]);

        if ($request->hasFile('passport_photo')) {
            $photoPath = DocumentService::storePublic($request->file('passport_photo'), 'avatars');
            $member->update(['passport_photo_path' => $photoPath]);
            $user->update(['avatar_path' => $photoPath]);
        }

        if (!empty($validated['vehicle_registration']) || !empty($validated['vehicle_type'])) {
            if ($member->primaryVehicle) {
                $member->primaryVehicle->update([
                    'vehicle_type' => $validated['vehicle_type'] ?? $member->primaryVehicle->vehicle_type,
                    'make' => $validated['vehicle_make'] ?? $member->primaryVehicle->make,
                    'model' => $validated['vehicle_model'] ?? $member->primaryVehicle->model,
                    'registration_number' => $validated['vehicle_registration'] ?? $member->primaryVehicle->registration_number,
                    'charging_type' => $validated['charging_type'] ?? $member->primaryVehicle->charging_type,
                ]);
            } else {
                \App\Models\Vehicle::create([
                    'member_id' => $member->id,
                    'vehicle_type' => $validated['vehicle_type'] ?? 'three_wheeler',
                    'make' => $validated['vehicle_make'] ?? null,
                    'model' => $validated['vehicle_model'] ?? null,
                    'registration_number' => $validated['vehicle_registration'] ?? null,
                    'charging_type' => $validated['charging_type'] ?? 'battery_swap',
                    'ownership_type' => 'driver_operated',
                ]);
            }
        }

        AuditLog::log('updated_profile', 'membership', (string)$member->id);

        return back()->with('success', 'Profile and vehicle information updated successfully.');
    }

    public function downloadCardPdf()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        if ($member->status !== 'approved' || !$member->membership_number) {
            return back()->with('error', 'Digital membership card is only available for approved active members.');
        }

        $pdf = PdfService::generateMembershipCardPdf($member);
        return $pdf->download('TEVDA-Card-' . $member->membership_number . '.pdf');
    }

    public function downloadCertificatePdf(int $id)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $certificate = Certificate::where('id', $id)
            ->where('member_id', $member->id)
            ->where('status', 'valid')
            ->firstOrFail();

        $pdf = PdfService::generateCertificatePdf($certificate);
        return $pdf->download($certificate->certificate_number . '.pdf');
    }

    public function training()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $programmes = TrainingProgramme::with(['courses.sessions' => function ($q) {
            $q->where('status', 'upcoming');
        }])->where('is_active', true)->get();

        $myEnrolments = TrainingEnrolment::with(['session.course.programme', 'result.certificate', 'attendances'])
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        return view('portal.training', compact('member', 'programmes', 'myEnrolments'));
    }

    public function enrollTraining(Request $request, int $sessionId)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        if ($member->status !== 'approved') {
            return back()->with('error', 'You must have an approved membership to register for certified TEVDA training sessions.');
        }

        $session = TrainingSession::with('course')->findOrFail($sessionId);

        // Check duplicate
        $existing = TrainingEnrolment::where('session_id', $session->id)->where('member_id', $member->id)->first();
        if ($existing) {
            return back()->with('info', 'You are already registered for this training session.');
        }

        $enrolment = TrainingEnrolment::create([
            'session_id' => $session->id,
            'member_id' => $member->id,
            'status' => 'confirmed',
            'registered_at' => now(),
        ]);

        AuditLog::log('enrolled_training', 'training', (string)$enrolment->id, null, ['session_code' => $session->session_code]);

        NotificationCustom::send(
            $user->id,
            'Enrolled in Training: ' . $session->course->title,
            "You have registered for session {$session->session_code} starting {$session->start_date->format('d M Y')}.",
            route('portal.training'),
            'success'
        );

        return back()->with('success', 'Successfully registered for ' . $session->course->title . '!');
    }

    public function opportunities()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $opportunities = Opportunity::with('category')->where('status', 'published')->orderBy('deadline', 'asc')->get();
        $myApplications = OpportunityApplication::with('opportunity')->where('member_id', $member->id)->get();

        return view('portal.opportunities', compact('member', 'opportunities', 'myApplications'));
    }

    public function applyOpportunity(Request $request, int $opportunityId)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $opportunity = Opportunity::findOrFail($opportunityId);

        if ($opportunity->application_type === 'external') {
            return redirect($opportunity->external_url);
        }

        // Check duplicate
        $existing = OpportunityApplication::where('opportunity_id', $opportunity->id)->where('member_id', $member->id)->first();
        if ($existing) {
            return back()->with('info', 'You have already applied for this opportunity.');
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:3000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $doc = DocumentService::storePrivate($request->file('resume'), 'resumes');
            $resumePath = $doc['file_path'];
        }

        $application = OpportunityApplication::create([
            'opportunity_id' => $opportunity->id,
            'member_id' => $member->id,
            'application_number' => OpportunityApplication::generateApplicationNumber(),
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $resumePath,
            'status' => 'submitted',
        ]);

        AuditLog::log('submitted_opportunity_app', 'opportunity', (string)$application->id);

        NotificationCustom::send(
            $user->id,
            'Application Submitted: ' . $opportunity->title,
            "Your application {$application->application_number} has been received for review.",
            route('portal.opportunities'),
            'success'
        );

        return back()->with('success', 'Application submitted successfully! Application ID: ' . $application->application_number);
    }

    public function projects()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $projects = Project::with('category')->get();
        $myApplications = ProjectApplication::with('project')->where('member_id', $member->id)->get();

        return view('portal.projects', compact('member', 'projects', 'myApplications'));
    }

    public function applyProject(Request $request, int $projectId)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $project = Project::findOrFail($projectId);

        if ($project->project_status !== 'open_for_applications') {
            return back()->with('error', 'Applications are not currently open for this project.');
        }

        $existing = ProjectApplication::where('project_id', $project->id)->where('member_id', $member->id)->first();
        if ($existing) {
            return back()->with('info', 'You have already submitted an application for this project.');
        }

        $validated = $request->validate([
            'statement_of_need' => 'required|string|max:3000',
            'preferred_vehicle_type' => 'nullable|string|max:50',
            'operating_zone_or_route' => 'nullable|string|max:100',
        ]);

        $app = ProjectApplication::create([
            'project_id' => $project->id,
            'member_id' => $member->id,
            'application_number' => ProjectApplication::generateApplicationNumber(),
            'statement_of_need' => $validated['statement_of_need'],
            'preferred_vehicle_type' => $validated['preferred_vehicle_type'] ?? null,
            'operating_zone_or_route' => $validated['operating_zone_or_route'] ?? null,
            'status' => 'applied',
        ]);

        AuditLog::log('applied_project', 'project', (string)$app->id);

        return back()->with('success', 'Project application submitted successfully! Reference: ' . $app->application_number);
    }

    public function payments()
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();

        $invoices = Invoice::with(['payments.receipt'])->where('member_id', $member->id)->latest()->get();

        return view('portal.payments', compact('member', 'invoices'));
    }

    public function submitPaymentProof(Request $request, int $invoiceId)
    {
        $user = Auth::user();
        $member = Member::where('user_id', $user->id)->firstOrFail();
        $invoice = Invoice::where('id', $invoiceId)->where('member_id', $member->id)->firstOrFail();

        $validated = $request->validate([
            'payment_method' => 'required|in:mobile_money,bank_transfer,cash,online_card,other',
            'transaction_reference' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'proof_file' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:5120',
        ]);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $doc = DocumentService::storePrivate($request->file('proof_file'), 'payments');
            $proofPath = $doc['file_path'];
        }

        $payment = Payment::create([
            'payment_reference' => Payment::generatePaymentReference(),
            'invoice_id' => $invoice->id,
            'member_id' => $member->id,
            'amount' => $validated['amount'],
            'currency' => $invoice->currency,
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'],
            'status' => 'pending',
            'proof_of_payment_path' => $proofPath,
        ]);

        AuditLog::log('submitted_payment_proof', 'payment', (string)$payment->id);

        NotificationCustom::send(
            $user->id,
            'Payment Submitted for Verification',
            "Payment reference {$payment->payment_reference} for Invoice {$invoice->invoice_number} submitted to TEVDA Finance.",
            route('portal.payments'),
            'info'
        );

        return back()->with('success', 'Payment proof submitted successfully! Our Finance Officer will verify your transaction reference.');
    }

    public function markNotificationAsRead(int $id)
    {
        $notification = NotificationCustom::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $notification->update(['is_read' => true, 'read_at' => now()]);

        if ($notification->link) {
            return redirect($notification->link);
        }

        return back();
    }
}
