<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardHRViewHelper;

class DashboardHRController extends Controller
{
    /**
     * Company details.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // is this person HR?
        if ($employee->permission_level > config('officelife.permission_level.hr')) {
            return redirect('home');
        }

        UpdateDashboardPreference::dispatch([
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'view' => 'hr',
        ])->onQueue('low');

        $employeeInformation = [
            'id' => $employee->id,
            'dashboard_view' => 'manager',
            'is_manager' => true,
            'can_manage_expenses' => $employee->can_manage_expenses,
            'can_manage_hr' => $employee->permission_level <= config('officelife.permission_level.hr'),
        ];

        $employeesWithoutManagersWithPendingTimesheets = DashboardHRViewHelper::employeesWithoutManagersWithPendingTimesheets($company);
        $statistics = DashboardHRViewHelper::statisticsAboutTimesheets($company);

        return Inertia::render('Dashboard/HR/Index', [
            'employee' => $employeeInformation,
            'notifications' => NotificationHelper::getNotifications($employee),
            'employeesWithPendingTimesheets' => $employeesWithoutManagersWithPendingTimesheets,
            'statistics' => $statistics,
        ]);
    }
}
