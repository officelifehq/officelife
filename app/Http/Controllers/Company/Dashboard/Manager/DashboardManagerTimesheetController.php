<?php

namespace App\Http\Controllers\Company\Dashboard\Manager;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\DirectReport;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Timesheet\RejectTimesheet;
use App\Services\Company\Employee\Timesheet\ApproveTimesheet;
use App\Http\ViewHelpers\Dashboard\DashboardTimesheetViewHelper;
use App\Http\ViewHelpers\Dashboard\Manager\DashboardManagerTimesheetViewHelper;

class DashboardManagerTimesheetController extends Controller
{
    /**
     * Show the list of timesheets to validate.
     *
     * @return mixed
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is the user a manager?
        $directReports = DirectReport::where('company_id', $company->id)
            ->where('manager_id', $employee->id)
            ->with('directReport')
            ->with('directReport.timesheets')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        $timesheetApprovals = DashboardManagerTimesheetViewHelper::timesheetApprovals($employee, $directReports);

        return Inertia::render('Dashboard/Manager/Timesheets/Index', [
            'employee' => [
                'id' => $employee->id,
            ],
            'notifications' => NotificationHelper::getNotifications($employee),
            'directReports' => $timesheetApprovals,
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
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $timesheet = $this->canAccess($company, $timesheetId, $loggedEmployee);

        $timesheetInformation = DashboardTimesheetViewHelper::show($timesheet);
        $daysInHeader = DashboardTimesheetViewHelper::daysHeader($timesheet);
        $approverInformation = DashboardTimesheetViewHelper::approverInformation($timesheet, $loggedEmployee);

        return Inertia::render('Dashboard/Manager/Timesheets/Show', [
            'employee' => [
                'id' => $loggedEmployee->id,
                'name' => $loggedEmployee->name,
            ],
            'daysHeader' => $daysInHeader,
            'timesheet' => $timesheetInformation,
            'approverInformation' => $approverInformation,
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Approve the timesheet.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     * @return JsonResponse
     */
    public function approve(Request $request, int $companyId, int $timesheetId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $timesheet = $this->canAccess($company, $timesheetId, $employee);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $timesheet->employee->id,
            'timesheet_id' => $timesheetId,
        ];

        $timesheet = (new ApproveTimesheet)->execute($data);

        return response()->json([
            'data' => $timesheet->id,
        ], 201);
    }

    /**
     * Reject the timesheet.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $timesheetId
     * @return JsonResponse
     */
    public function reject(Request $request, int $companyId, int $timesheetId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $timesheet = $this->canAccess($company, $timesheetId, $employee);

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $timesheet->employee->id,
            'timesheet_id' => $timesheetId,
        ];

        $timesheet = (new RejectTimesheet)->execute($data);

        return response()->json([
            'data' => $timesheet->id,
        ], 201);
    }

    /**
     * Check that the current employee has access to this method.
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

        if ($timesheet->status !== Timesheet::READY_TO_SUBMIT) {
            return redirect('home');
        }

        // is the user a manager?
        $directReports = DirectReport::where('company_id', $company->id)
            ->where('manager_id', $employee->id)
            ->with('directReport')
            ->with('directReport.timesheets')
            ->get();

        if ($directReports->count() == 0) {
            return redirect('home');
        }

        // can the manager see this timesheet?
        if (! $employee->isManagerOf($timesheet->employee->id)) {
            return redirect('home');
        }

        return $timesheet;
    }
}
