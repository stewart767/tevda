<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Opportunity;
use App\Models\OpportunityCategory;
use App\Models\OpportunityApplication;
use App\Models\Member;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpportunityController extends BaseApiController
{
    /**
     * Searchable list of verified opportunities
     */
    public function index(Request $request): JsonResponse
    {
        $query = Opportunity::with(['category'])
            ->where('status', 'open');

        if ($request->has('category_id') && !empty($request->category_id)) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('provider_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $opportunities = $query->latest()->paginate(15);
        $categories = OpportunityCategory::all();

        return $this->successResponse([
            'categories' => $categories,
            'opportunities' => $opportunities,
        ], 'Fursa zilizothibitishwa za TEVDA.');
    }

    /**
     * Detail of opportunity
     */
    public function show(int $id): JsonResponse
    {
        $opportunity = Opportunity::with(['category'])->find($id);
        if (!$opportunity) {
            return $this->errorResponse('Fursa haijapatikana.', 404);
        }

        return $this->successResponse($opportunity, 'Maelezo ya fursa.');
    }

    /**
     * Apply for an opportunity
     */
    public function apply(Request $request, int $id): JsonResponse
    {
        $opportunity = Opportunity::find($id);
        if (!$opportunity) {
            return $this->errorResponse('Fursa haijapatikana.', 404);
        }

        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $application = OpportunityApplication::firstOrCreate(
            [
                'opportunity_id' => $opportunity->id,
                'member_id' => $member->id,
            ],
            [
                'application_date' => now(),
                'status' => 'submitted',
                'applicant_notes' => $request->input('notes', 'Maombi ya mwanachama kupitia TEVDA Mobile App'),
            ]
        );

        return $this->successResponse($application, 'Maombi yako yamewasilishwa kwa mtoa fursa.', 201);
    }

    /**
     * My Applications
     */
    public function myApplications(Request $request): JsonResponse
    {
        $member = Member::first();
        if (!$member) {
            return $this->errorResponse('Mwanachama hajapatikana.', 404);
        }

        $applications = OpportunityApplication::with(['opportunity.category'])
            ->where('member_id', $member->id)
            ->latest()
            ->get();

        return $this->successResponse($applications, 'Historia ya maombi ya fursa.');
    }
}
