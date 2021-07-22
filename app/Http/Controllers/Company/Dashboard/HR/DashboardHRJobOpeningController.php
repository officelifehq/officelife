<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRTimesheetViewHelper;
use App\Http\ViewHelpers\Dashboard\HR\DashboardHRJobOpeningsViewHelper;

class DashboardHRJobOpeningController extends Controller
{
    /**
     * Show the list of job openings.
     *
     * @return mixed
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        $employees = DashboardHRTimesheetViewHelper::timesheetApprovalsForEmployeesWithoutManagers($company);

        return Inertia::render('Dashboard/HR/Timesheets/Index', [
            'employee' => [
                'id' => $employee->id,
            ],
            'notifications' => NotificationHelper::getNotifications($employee),
            'employees' => $employees,
        ]);
    }

    /**
     * Show the Create job opening view.
     *
     * @param Request $request
     * @param int $companyId
     * @return mixed
     */
    public function create(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        $positions = DashboardHRJobOpeningsViewHelper::positions($company);

        return Inertia::render('Dashboard/HR/JobOpenings/Create', [
            'positions' => $positions,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Get the list of potential sponsors for this job opening.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
     */
    public function sponsors(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        $potentialSponsors = DashboardHRJobOpeningsViewHelper::potentialSponsors(
            $company,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $potentialSponsors,
        ]);
    }
}
