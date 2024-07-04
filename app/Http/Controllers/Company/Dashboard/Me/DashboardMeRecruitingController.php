<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\JobOpening\CreateCandidateStageNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardMeRecruitingController extends Controller
{
    /**
     * Add a note as a participant of a recruiting process.
     */
    public function store(Request $request, int $companyId, int $jobOpeningId, int $candidateId, int $candidateStageId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'author_id' => $employee->id,
            'company_id' => $company->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'candidate_stage_id' => $candidateStageId,
            'note' => $request->input('note'),
        ];

        (new CreateCandidateStageNote)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
