<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;

class CompanyController extends Controller
{
    /**
     * All the questions in the company, for public use.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        $questions = $company->questions()->count();

        return Inertia::render('Company/Index', [
            'questions' => $questions,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
