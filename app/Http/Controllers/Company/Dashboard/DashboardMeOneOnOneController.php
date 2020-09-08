<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;

class DashboardMeOneOnOneController extends Controller
{
    /**
     * Show the.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

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
