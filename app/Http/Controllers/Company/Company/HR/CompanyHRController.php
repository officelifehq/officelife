<?php

namespace App\Http\Controllers\Company\Company\HR;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use App\Http\ViewHelpers\Company\HR\CompanyHRViewHelper;

class CompanyHRController extends Controller
{
    /**
     * Show the HR main page on the Company tab.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $genders = CompanyHRViewHelper::genderStats($company);
        $eCoffees = CompanyHRViewHelper::eCoffees($company);
        $positions = CompanyHRViewHelper::positions($company);
        $ama = CompanyHRViewHelper::askMeAnythingUpcomingSession($company);
        $statistics = CompanyViewHelper::information($company);
        $jobOpenings = CompanyHRViewHelper::openedJobOpenings($company);

        return Inertia::render('Company/HR/Index', [
            'tab' => 'hr',
            'eCoffees' => $eCoffees,
            'statistics' => $statistics,
            'genders' => $genders,
            'positions' => $positions,
            'askMeAnythingSession' => $ama,
            'jobOpenings' => $jobOpenings,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }
}
