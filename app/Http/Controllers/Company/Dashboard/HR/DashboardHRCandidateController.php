<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Candidate;
use Illuminate\Http\JsonResponse;
use App\Models\Company\JobOpening;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\ProcessCandidateStage;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRCandidatesViewHelper;

class DashboardHRCandidateController extends Controller
{
    /**
     * Show the detail of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @return mixed
     */
    public function show(Request $request, int $companyId, int $jobOpeningId, int $candidateId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        try {
            $jobOpening = JobOpening::where('company_id', $company->id)
                ->with('team')
                ->with('position')
                ->with('sponsors')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('dashboard.hr.openings.index');
        }

        try {
            $candidate = Candidate::where('company_id', $company->id)
                ->findOrFail($candidateId);
        } catch (ModelNotFoundException $e) {
            return redirect('dashboard.hr.openings.index');
        }

        $info = DashboardHRCandidatesViewHelper::jobOpening($company, $jobOpening);
        $candidate = DashboardHRCandidatesViewHelper::candidate($company, $jobOpening, $candidate);

        return Inertia::render('Dashboard/HR/JobOpenings/Candidates/Show', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'jobOpening' => $info,
            'candidate' => $candidate,
        ]);
    }

    /**
     * Show the detail of a job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @param integer $candidateId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $jobOpeningId, int $candidateId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new ProcessCandidateStage)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'job_opening_id' => $jobOpeningId,
            'candidate_id' => $candidateId,
            'accepted' => $request->input('accepted'),
        ]);

        return response()->json([
            'data' => true,
        ]);
    }
}
