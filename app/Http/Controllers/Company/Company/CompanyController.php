<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanyViewHelper;

class CompanyController extends Controller
{
    /**
     * All the information about the company, for public use.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $questions = $company->questions()->count();
        $skills = $company->skills()->count();
        $latestQuestions = CompanyViewHelper::latestQuestions($company);
        $birthdaysThisWeek = CompanyViewHelper::birthdaysThisWeek($company);

        return Inertia::render('Company/Index', [
            'latestQuestions' => $latestQuestions,
            'birthdaysThisWeek' => $birthdaysThisWeek,
            'questions' => $questions,
            'questionsUrl' => route('company.questions.index', [
                'company' => $company->id,
            ]),
            'skills' => $skills,
            'skillsUrl' => route('company.skills.index', [
                'company' => $company->id,
            ]),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
