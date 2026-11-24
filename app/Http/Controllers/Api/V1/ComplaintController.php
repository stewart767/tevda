<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Complaint;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ComplaintController extends BaseApiController
{
    /**
     * Submit an incident or complaint report
     * CRITICAL SECURITY: When is_anonymous is true, reporter_name, phone, email,
     * and member linkage MUST NEVER be recorded.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string', // mapped to enum
            'description' => 'required|string|min:10',
            'is_anonymous' => 'required|boolean',
            'location' => 'nullable|string',
            'priority' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Tafadhali kamilisha maelezo ya ripoti.', 422, $validator->errors());
        }

        // Map mobile categories to database enum:
        // ['fraud', 'misconduct', 'discrimination', 'unsafe_practices', 'misuse_of_name', 'other']
        $inputCat = strtolower($request->input('category'));
        $categoryEnum = match ($inputCat) {
            'extortion', 'fraud' => 'fraud',
            'police_harassment', 'misconduct' => 'misconduct',
            'discrimination' => 'discrimination',
            'accidents', 'battery_theft', 'unsafe_practices', 'charging_infrastructure', 'vehicle_defect' => 'unsafe_practices',
            'misuse_of_name' => 'misuse_of_name',
            default => 'other',
        };

        $isAnonymous = filter_var($request->input('is_anonymous'), FILTER_VALIDATE_BOOLEAN);
        $complaintNumber = Complaint::generateComplaintNumber();

        $evidencePath = null;
        if ($request->hasFile('evidence')) {
            $evidencePath = $request->file('evidence')->store('complaint_evidence', 'public');
        }

        $reporterName = null;
        $reporterPhone = null;
        $reporterEmail = null;

        if (!$isAnonymous) {
            $member = Member::first();
            $reporterName = $request->input('reporter_name') ?: ($member ? $member->full_name : 'Driver');
            $reporterPhone = $request->input('reporter_phone') ?: ($member ? $member->phone : null);
            $reporterEmail = $request->input('reporter_email') ?: ($member ? $member->email : null);
        }

        $complaint = Complaint::create([
            'complaint_number' => $complaintNumber,
            'category' => $categoryEnum,
            'description' => $request->input('description'),
            'evidence_file_path' => $evidencePath,
            'reporter_name' => $reporterName,
            'reporter_phone' => $reporterPhone,
            'reporter_email' => $reporterEmail,
            'is_anonymous' => $isAnonymous,
            'priority' => $request->input('priority', 'medium'),
            'status' => 'submitted',
        ]);

        return $this->successResponse([
            'ticket_number' => $complaint->complaint_number,
            'category' => $complaint->category,
            'is_anonymous' => $isAnonymous,
            'status' => $complaint->status,
            'created_at' => $complaint->created_at->format('d M Y H:i'),
            'notice' => $isAnonymous 
                ? 'Ripoti imepokewa bila utambulisho wowote. Hifadhi namba ya tiketi ili kufuatilia hatua zilizochukuliwa.'
                : 'Ripoti imepokelewa na Afisa Mwandamizi wa TEVDA atawasiliana nawe kwa usaidizi wa haraka.',
        ], 'Ripoti ya usalama imewasilishwa kwa ulinzi mkali.', 201);
    }

    /**
     * Track a complaint ticket by ticket number
     */
    public function track(string $ticketNumber): JsonResponse
    {
        $complaint = Complaint::where('complaint_number', $ticketNumber)->first();
        if (!$complaint) {
            return $this->errorResponse('Namba hii ya tiketi haijapatikana kwenye mfumo wa usalama.', 404);
        }

        return $this->successResponse([
            'ticket_number' => $complaint->complaint_number,
            'category' => $complaint->category,
            'status' => $complaint->status,
            'is_anonymous' => $complaint->is_anonymous,
            'created_at' => $complaint->created_at->format('d M Y H:i'),
            'resolution_summary' => $complaint->resolution_summary ?: 'Ripoti inafanyiwa kazi na timu ya usalama na sheria ya TEVDA.',
            'closed_at' => $complaint->closed_at ? $complaint->closed_at->format('d M Y H:i') : null,
        ], 'Hali ya tiketi ya usalama.');
    }

    /**
     * List member's identified complaints
     */
    public function myComplaints(Request $request): JsonResponse
    {
        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        // Only fetch identified complaints with member phone
        $complaints = Complaint::where('is_anonymous', false)
            ->where(function ($q) use ($member) {
                $q->where('reporter_phone', $member->phone)
                  ->orWhere('reporter_name', $member->full_name);
            })
            ->latest()
            ->get();

        return $this->successResponse($complaints, 'Ripoti zako za usalama.');
    }
}
