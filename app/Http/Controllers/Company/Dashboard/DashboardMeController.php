<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
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

        $employeeInformation = [
            'id' => $employee->id,
            'dashboard_view' => 'me',
            'can_manage_expenses' => $employee->can_manage_expenses,
            'is_manager' => $employee->directReports->count() > 0,
            'question' => DashboardMeViewHelper::question($employee),
            'can_manage_hr' => $employee->permission_level <= config('officelife.permission_level.hr'),
        ];

        return Inertia::render('Dashboard/Me/Index', [
            'employee' => $employeeInformation,
            'notifications' => NotificationHelper::getNotifications($employee),
            'ownerPermissionLevel' => config('officelife.permission_level.administrator'),
            'tasks' => DashboardMeViewHelper::tasks($employee),
            'categories' => DashboardMeViewHelper::categories($company),
            'currencies' => DashboardMeViewHelper::currencies(),
            'expenses' => DashboardMeViewHelper::expenses($employee),
            'rateYourManagerAnswers' => DashboardMeViewHelper::rateYourManagerAnswers($employee),
            'oneOnOnes' => DashboardMeViewHelper::oneOnOnes($employee),
            'contractRenewal' => DashboardMeViewHelper::contractRenewal($employee),
            'defaultCurrency' => DashboardMeViewHelper::companyCurrency($company),
            'eCoffee' => DashboardMeViewHelper::eCoffee($employee, $company),
            'projects' => DashboardMeViewHelper::projects($employee, $company),
            'worklogs' => DashboardMeViewHelper::worklogs($employee),
            'morale' => DashboardMeViewHelper::morale($employee),
            'workFromHome' => DashboardMeViewHelper::workFromHome($employee),
        ]);
    }
}
