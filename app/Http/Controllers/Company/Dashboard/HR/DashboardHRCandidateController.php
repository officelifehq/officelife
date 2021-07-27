<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\JobOpening;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRJobOpeningsViewHelper;

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

        $jobOpening = DashboardHRJobOpeningsViewHelper::show($company, $jobOpening);

        return Inertia::render('Dashboard/HR/JobOpenings/Candidates/Show', [
            'notifications' => NotificationHelper::getNotifications($employee),
            'jobOpening' => $jobOpening,
        ]);
    }
}
