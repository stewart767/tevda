<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\TrainingCourse;
use App\Models\TrainingModule;
use App\Models\TrainingSession;
use App\Models\TrainingEnrolment;
use App\Models\TrainingAssessment;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademyController extends BaseApiController
{
    /**
     * List all official training courses
     */
    public function courses(Request $request): JsonResponse
    {
        $courses = TrainingCourse::with(['programme', 'modules'])
            ->where('is_active', true)
            ->get();

        return $this->successResponse($courses, 'Kozi rasmi za TEVDA Academy.');
    }

    /**
     * Get Course Detail with Modules and Lessons
     */
    public function show(int $id): JsonResponse
    {
        $course = TrainingCourse::with(['programme', 'modules', 'assessments'])->find($id);
        if (!$course) {
            return $this->errorResponse('Kozi haijapatikana.', 404);
        }

        return $this->successResponse($course, 'Maelezo ya kozi.');
    }

    /**
     * Enroll member in a course
     */
    public function enroll(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:training_courses,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Chagua kozi sahihi.', 422, $validator->errors());
        }

        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $course = TrainingCourse::find($request->input('course_id'));
        $session = TrainingSession::where('course_id', $course->id)->first();
        if (!$session) {
            $session = TrainingSession::create([
                'course_id' => $course->id,
                'session_code' => 'SESS-' . $course->id . '-' . date('Y'),
                'location' => 'Dar es Salaam HQ / Online',
                'start_date' => now(),
                'status' => 'upcoming',
            ]);
        }

        $enrolment = TrainingEnrolment::firstOrCreate(
            [
                'session_id' => $session->id,
                'member_id' => $member->id,
            ],
            [
                'status' => 'registered',
                'registered_at' => now(),
            ]
        );

        return $this->successResponse($enrolment, 'Umejiandikisha katika kozi kwa mafanikio.');
    }

    /**
     * Get member progress across all courses
     */
    public function myProgress(Request $request): JsonResponse
    {
        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $enrolments = TrainingEnrolment::with(['course'])
            ->where('member_id', $member->id)
            ->get();

        return $this->successResponse($enrolments, 'Maendeleo ya masomo ya mwanachama.');
    }

    /**
     * Submit Course Assessment / Quiz
     */
    public function submitAssessment(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:training_courses,id',
            'score_percentage' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Matokeo hayakukamilika.', 422, $validator->errors());
        }

        $course = TrainingCourse::find($request->input('course_id'));
        $member = Member::first();

        $score = (float) $request->input('score_percentage');
        $passed = ($score >= (float) $course->pass_mark_percentage);

        return $this->successResponse([
            'course_id' => $course->id,
            'course_title' => $course->title,
            'pass_mark' => $course->pass_mark_percentage,
            'member_score' => $score,
            'passed' => $passed,
            'status' => $passed ? 'passed' : 'retake_recommended',
            'certificate_eligible' => $passed,
            'feedback' => $passed 
                ? 'Hongera sana! Umefaulu mtihani huu na kupata alama za juu.' 
                : 'Hujafikisha kiwango cha ufaulu cha asilimia ' . $course->pass_mark_percentage . '%. Rudia kusoma mada husika.',
        ], 'Mtihani umewasilishwa na kusahihishwa.');
    }
}
