<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\JobOpening\CreateCandidateStageNote;

class DashboardMeRecruitingController extends Controller
{
    /**
     * Add a note as a participant of a recruiting process.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $jobOpeningId
     * @param int $candidateId
     * @param int $candidateStageId
     * @return JsonResponse
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
