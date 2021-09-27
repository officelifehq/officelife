<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Question;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanyNewsViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\CompanyQuestionViewHelper;

class CompanyNewsController extends Controller
{
    /**
     * All the company news in the company.
     *
     * @return Response
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
     * @param Request $request
     * @param int $companyId
     * @param int $questionId
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
            'teams' =>$teams,
            'question' => $answersCollection,
            'notifications' => NotificationHelper::getNotifications($employee),
            'paginator' => PaginatorHelper::getData($answers),
        ]);
    }
}
