<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRJobOpeningsViewHelper;

class DashboardSponsorJobOpeningController extends Controller
{
    /**
     * Display the job opening as the sponsor.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $jobOpeningId
     * @return JsonResponse
     */
    public function show(Request $request, int $companyId, int $jobOpeningId): mixed
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // is this person the sponsor of the job?
        try {
            DB::table('job_opening_sponsor')->where('employee_id', $loggedEmployee->id)
                ->where('job_opening_id', $jobOpeningId)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $jobOpening = JobOpening::where('company_id', $company->id)
                ->with('team')
                ->with('position')
                ->with('sponsors')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $jobOpening = JobOpening::where('company_id', $company->id)
                ->with('team')
                ->with('position')
                ->with('position.employees')
                ->with('sponsors')
                ->with('template')
                ->with('template.stages')
                ->findOrFail($jobOpeningId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $jobOpeningDetail = DashboardHRJobOpeningsViewHelper::show($company, $jobOpening);
        $sponsors = DashboardHRJobOpeningsViewHelper::sponsors($company, $jobOpening);
        $stats = DashboardHRJobOpeningsViewHelper::stats($company, $jobOpening);
        $candidates = DashboardHRJobOpeningsViewHelper::toSort($company, $jobOpening);

        return Inertia::render('Dashboard/Me/JobOpenings/Show', [
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
            'jobOpening' => $jobOpeningDetail,
            'sponsors' => $sponsors,
            'stats' => $stats,
            'candidates' => $candidates,
            'tab' => 'to_sort',
        ]);
    }
}
