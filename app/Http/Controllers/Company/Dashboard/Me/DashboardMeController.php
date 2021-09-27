<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Jobs\UpdateDashboardPreference;
use App\Http\ViewHelpers\Dashboard\DashboardViewHelper;
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

        return Inertia::render('Dashboard/Me/Index', [
            'employee' => DashboardViewHelper::information($employee, 'me'),
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
            'question' => DashboardMeViewHelper::question($employee),
            'jobOpeningsAsSponsor' => DashboardMeViewHelper::jobOpeningsAsSponsor($company, $employee),
            'jobOpeningsAsParticipant' => DashboardMeViewHelper::jobOpeningsAsParticipant($employee),
            'askMeAnything' => DashboardMeViewHelper::activeAskMeAnythingSession($company, $employee),
        ]);
    }
}
