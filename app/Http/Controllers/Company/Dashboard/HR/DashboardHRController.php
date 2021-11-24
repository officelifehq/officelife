<?php

namespace App\Http\Controllers\Company\Dashboard\HR;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardViewHelper;
use App\Http\ViewHelpers\Dashboard\DashboardHRViewHelper;

class DashboardHRController extends Controller
{
    /**
     * Index of the HR tab on the dashboard.
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

        $employeesWithoutManagersWithPendingTimesheets = DashboardHRViewHelper::employeesWithoutManagersWithPendingTimesheets($company);
        $statisticsAboutTimesheets = DashboardHRViewHelper::statisticsAboutTimesheets($company);

        return Inertia::render('Dashboard/HR/Index', [
            'employee' => DashboardViewHelper::information($employee, 'hr'),
            'notifications' => NotificationHelper::getNotifications($employee),
            'employeesWithoutManagersWithPendingTimesheets' => $employeesWithoutManagersWithPendingTimesheets,
            'statisticsAboutTimesheets' => $statisticsAboutTimesheets,
            'disciplineCases' => DashboardHRViewHelper::disciplineCases($company),
        ]);
    }
}
