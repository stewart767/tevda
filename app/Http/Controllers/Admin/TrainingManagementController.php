<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingProgramme;
use App\Models\TrainingCourse;
use App\Models\TrainingModule;
use App\Models\TrainingSession;
use App\Models\TrainingEnrolment;
use App\Models\TrainingAttendance;
use App\Models\TrainingAssessment;
use App\Models\TrainingResult;
use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\AuditLog;
use App\Models\NotificationCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TrainingManagementController extends Controller
{
    public function index()
    {
        $programmes = TrainingProgramme::withCount('courses')->orderBy('order_number')->get();
        $sessions = TrainingSession::with(['course.programme', 'enrolments'])->latest()->paginate(10);
        $totalEnrolments = TrainingEnrolment::count();
        $certificatesIssued = Certificate::where('certificate_type', 'training_completion')->count();

        return view('admin.training.index', compact('programmes', 'sessions', 'totalEnrolments', 'certificatesIssued'));
    }

    public function showSession(int $id)
    {
        $session = TrainingSession::with([
            'course.programme',
            'course.modules',
            'course.assessments',
            'enrolments.member.category',
            'enrolments.attendances',
            'enrolments.result.certificate',
            'assessment'
        ])->findOrFail($id);

        return view('admin.training.session_show', compact('session'));
    }

    public function createSession(Request $request)
    {
        $courses = TrainingCourse::with('programme')->where('is_active', true)->get();
        return view('admin.training.session_create', compact('courses'));
    }

    public function storeSession(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:training_courses,id',
            'trainer_name' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'venue' => 'nullable|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'capacity' => 'required|integer|min:1',
            'fee_amount' => 'nullable|numeric|min:0',
        ]);

        $validated['session_code'] = TrainingSession::generateSessionCode();
        $validated['fee_amount'] = $validated['fee_amount'] ?? 0.00;

        $session = TrainingSession::create($validated);
        AuditLog::log('created_training_session', 'training', (string)$session->id, null, ['session_code' => $session->session_code]);

        return redirect()->route('admin.training.session.show', $session->id)->with('success', 'Training session created successfully.');
    }

    public function recordAttendance(Request $request, int $sessionId)
    {
        $request->validate([
            'enrolment_id' => 'required|exists:training_enrolments,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,excused',
        ]);

        $enrolment = TrainingEnrolment::findOrFail($request->enrolment_id);

        $attendance = TrainingAttendance::updateOrCreate(
            [
                'session_id' => $sessionId,
                'enrolment_id' => $enrolment->id,
                'member_id' => $enrolment->member_id,
                'attendance_date' => $request->attendance_date,
            ],
            [
                'status' => $request->status,
                'recorded_by' => Auth::id(),
                'method' => 'manual',
            ]
        );

        return back()->with('success', 'Attendance recorded successfully.');
    }

    public function recordAssessmentResult(Request $request, int $sessionId)
    {
        $request->validate([
            'enrolment_id' => 'required|exists:training_enrolments,id',
            'score' => 'required|numeric|min:0|max:100',
        ]);

        $session = TrainingSession::with('course')->findOrFail($sessionId);
        $enrolment = TrainingEnrolment::with('member')->findOrFail($request->enrolment_id);
        $course = $session->course;

        $passMark = $course->pass_mark_percentage ?? 70.00;
        $score = $request->score;
        $status = ($score >= $passMark) ? 'pass' : 'fail';

        DB::beginTransaction();
        try {
            // Find or create default assessment record for this course
            $assessment = TrainingAssessment::firstOrCreate(
                ['course_id' => $course->id],
                [
                    'title' => $course->title . ' Standard Assessment',
                    'total_marks' => 100.00,
                    'passing_marks' => $passMark,
                    'is_active' => true,
                ]
            );

            $result = TrainingResult::updateOrCreate(
                ['enrolment_id' => $enrolment->id, 'assessment_id' => $assessment->id],
                [
                    'member_id' => $enrolment->member_id,
                    'score' => $score,
                    'percentage' => $score,
                    'status' => $status,
                    'evaluated_by' => Auth::id(),
                    'evaluated_at' => now(),
                ]
            );

            // If passed, issue Training Completion Certificate automatically
            if ($status === 'pass') {
                $enrolment->update(['status' => 'completed']);

                $template = CertificateTemplate::where('certificate_type', 'training_completion')->first();
                $certNumber = Certificate::generateCertificateNumber('TRN');
                $verifyUrl = url('/verify/certificate/' . $certNumber);

                $certificate = Certificate::create([
                    'certificate_number' => $certNumber,
                    'member_id' => $enrolment->member_id,
                    'template_id' => $template?->id,
                    'certificate_type' => 'training_completion',
                    'title' => 'Certificate of Training Completion',
                    'recipient_name' => $enrolment->member->full_name,
                    'course_id' => $course->id,
                    'course_name' => $course->title,
                    'grade' => $score >= 85 ? 'Distinction' : ($score >= 75 ? 'Credit' : 'Pass'),
                    'issue_date' => now(),
                    'authorized_person_name' => 'Dr. Charles Mwansasu',
                    'authorized_person_title' => 'Founding Chairperson / Training Directorate',
                    'qr_code_path' => $verifyUrl,
                    'status' => 'valid',
                ]);

                $result->update(['certificate_id' => $certificate->id]);

                NotificationCustom::send(
                    $enrolment->member->user_id,
                    'Training Passed & Certificate Issued!',
                    "Congratulations! You scored {$score}% in {$course->title}. Your Certificate {$certNumber} has been issued and is available in your portal.",
                    route('portal.training'),
                    'success'
                );
            } else {
                $enrolment->update(['status' => 'failed']);
            }

            AuditLog::log('evaluated_training_assessment', 'training', (string)$result->id, null, [
                'score' => $score,
                'status' => $status,
                'member' => $enrolment->member->full_name,
            ]);

            DB::commit();

            return back()->with('success', "Result recorded: {$score}% ({$status}). Certificate automatically generated if passed.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Result processing failed: ' . $e->getMessage()]);
        }
    }
}
