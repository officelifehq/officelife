<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\JobOpening\CreateJobOpening;
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
        $teams = DashboardHRJobOpeningsViewHelper::teams($company);

        return Inertia::render('Dashboard/HR/JobOpenings/Create', [
            'positions' => $positions,
            'teams' => $teams,
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

    /**
     * Store the new job opening.
     *
     * @param Request $request
     * @param integer $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'position_id' => $request->input('position'),
            'sponsors' => $request->input('sponsorsId'),
            'team_id' => $request->input('teamId'),
            'title' => $request->input('title'),
            'reference_number' => $request->input('reference_number'),
            'description' => $request->input('description'),
        ];

        $company = (new CreateJobOpening)->execute($data);

        return response()->json([
            'data' => [
                'enabled' => $company->e_coffee_enabled,
            ],
        ], 200);
    }
}
