<?php

namespace App\Http\Controllers\Company\Dashboard;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardTimesheetViewHelper;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;

class DashboardTimesheetController extends Controller
{
    /**
     * See the detail of the current timesheet.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        UpdateDashboardPreference::dispatch([
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'view' => 'timesheet',
        ])->onQueue('low');

        $employeeInformation = [
            'id' => $employee->id,
            'dashboard_view' => 'timesheet',
            'can_manage_expenses' => $employee->can_manage_expenses,
            'is_manager' => $employee->directReports->count() > 0,
        ];

        $currentTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $timesheet = DashboardTimesheetViewHelper::show($currentTimesheet, $employee);

        return Inertia::render('Dashboard/Timesheet/Index', [
            'employee' => $employeeInformation,
            'timesheet' => $timesheet,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
