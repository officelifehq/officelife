<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Models\Company\Answer;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Question;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\CompanyQuestionViewHelper;

class QuestionController extends Controller
{
    /**
     * All the questions in the company, for public use.
     *
     * @return Response
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $questionsCollection = CompanyQuestionViewHelper::questions($company);

        return Inertia::render('Company/Question/Index', [
            'questions' => $questionsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
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
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($answers),
        ]);
    }

    /**
     * Get the detail of a given question for a specific team.
     *
     * @param  Request $request
     * @param  int $companyId
     * @param  int $questionId
     * @param  int $teamId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function team(Request $request, int $companyId, int $questionId, int $teamId)
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

        // make sure the team belongs to the company
        try {
            $team = Team::where('company_id', $companyId)
                ->with('employees')
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $allEmployeesIDsInTeam = $team->employees->pluck('id')->toArray();
        $answers = Answer::where('question_id', $question->id)
            ->whereIn('employee_id', $allEmployeesIDsInTeam)
            ->paginate(10);
        $answersCollection = CompanyQuestionViewHelper::question($question, $answers, $employee);

        $teams = CompanyQuestionViewHelper::teams($company->teams);

        return Inertia::render('Company/Question/Show', [
            'teams' => $teams,
            'currentTeam' => $teamId,
            'question' => $answersCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($answers),
        ]);
    }
}
