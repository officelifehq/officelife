<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Dashboard\DashboardTimesheetViewHelper;
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
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
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
     * Show the timesheet to validate.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function show(Request $request, int $companyId, int $timesheetId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $timesheet = $this->canAccess($company, $timesheetId, $employee);

        $timesheetInformation = DashboardTimesheetViewHelper::show($timesheet);
        $daysInHeader = DashboardTimesheetViewHelper::daysHeader($timesheet);
        $approverInformation = DashboardTimesheetViewHelper::approverInformation($timesheet, $employee);

        return Inertia::render('Dashboard/HR/Timesheets/Show', [
            'employee' => [
                'id' => $employee->id,
                'name' => $employee->name,
            ],
            'daysHeader' => $daysInHeader,
            'timesheet' => $timesheetInformation,
            'approverInformation' => $approverInformation,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Check that the current employee has access to this method.
     *
     * @param Company $company
     * @param int $timesheetId
     * @param Employee $employee
     * @return mixed
     */
    private function canAccess(Company $company, int $timesheetId, Employee $employee)
    {
        try {
            $timesheet = Timesheet::where('company_id', $company->id)
                ->findOrFail($timesheetId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        return $timesheet;
    }
}
