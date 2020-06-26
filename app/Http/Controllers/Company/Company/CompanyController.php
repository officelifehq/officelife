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
        $skills = $company->skills()->count();

        return Inertia::render('Company/Index', [
            'questions' => $questions,
            'questions_url' => route('company.questions.index', [
                'company' => $company->id,
            ]),
            'skills' => $skills,
            'skills_url' => route('company.skills.index', [
                'company' => $company->id,
            ]),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
