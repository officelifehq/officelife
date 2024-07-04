<?php

namespace App\Http\Controllers\Company\Company;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Helpers\PaginatorHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanyNewsViewHelper;
use App\Http\ViewHelpers\Company\CompanyQuestionViewHelper;
use App\Models\Company\Company;
use App\Models\Company\Question;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CompanyNewsController extends Controller
{
    /**
     * All the company news in the company.
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $newsCollection = CompanyNewsViewHelper::index($company, $employee);

        return Inertia::render('Company/News/Index', [
            'news' => $newsCollection,
            'notifications' => NotificationHelper::getNotifications($employee),
        ]);
    }

    /**
     * Get the detail of a given question.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, int $questionId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // make sure the question belongs to the company
        try {
            $question = Question::where('company_id', $companyId)
                ->findOrFail($questionId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $teams = CompanyQuestionViewHelper::teams($company->teams);
        $answers = $question->answers()->orderBy('created_at', 'desc')->paginate(10);
        $answersCollection = CompanyQuestionViewHelper::question($question, $answers, $employee);

        return Inertia::render('Company/Question/Show', [
            'teams' => $teams,
            'question' => $answersCollection,
            'notifications' => NotificationHelper::getNotifications($employee),
            'paginator' => PaginatorHelper::getData($answers),
        ]);
    }
}
