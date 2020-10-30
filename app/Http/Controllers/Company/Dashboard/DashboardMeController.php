<?php

namespace App\Http\Controllers\Company\Dashboard;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Helpers\WorkFromHomeHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardMeViewHelper;

class DashboardMeController extends Controller
{
    /**
     * Company details.
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
            'view' => 'me',
        ])->onQueue('low');

        $worklogCount = $employee->worklogs()->count();

        $employeeInformation = [
            'id' => $employee->id,
            'has_logged_worklog_today' => $employee->hasAlreadyLoggedWorklogToday(),
            'has_logged_morale_today' => $employee->hasAlreadyLoggedMoraleToday(),
            'dashboard_view' => 'me',
            'can_manage_expenses' => $employee->can_manage_expenses,
            'is_manager' => $employee->directReports->count() > 0,
            'has_worked_from_home_today' => WorkFromHomeHelper::hasWorkedFromHomeOnDate($employee, Carbon::now()),
            'question' => DashboardMeViewHelper::question($employee),
        ];

        $defaultCompanyCurrency = [
            'id' => $company->currency,
            'code' => $company->currency,
        ];

        return Inertia::render('Dashboard/Me/Index', [
            'employee' => $employeeInformation,
            'worklogCount' => $worklogCount,
            'notifications' => NotificationHelper::getNotifications($employee),
            'ownerPermissionLevel' => config('officelife.permission_level.administrator'),
            'tasks' => DashboardMeViewHelper::tasks($employee),
            'categories' => DashboardMeViewHelper::categories($company),
            'currencies' => DashboardMeViewHelper::currencies(),
            'expenses' => DashboardMeViewHelper::expenses($employee),
            'rateYourManagerAnswers' => DashboardMeViewHelper::rateYourManagerAnswers($employee),
            'oneOnOnes' => DashboardMeViewHelper::oneOnOnes($employee),
            'defaultCurrency' => $defaultCompanyCurrency,
        ]);
    }
}
