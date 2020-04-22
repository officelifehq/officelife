<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\TeamCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Company\CompanyQuestionViewHelper;

class QuestionController extends Controller
{
    /**
     * All the questions in the company, for public use.
     *
     * @return \Inertia\Response
     */
    public function index()
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
     * @return JsonResponse
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

        $teams = $company->teams;
        $answers = $question->answers()->orderBy('created_at', 'desc')->paginate(10);
        $answersCollection = CompanyQuestionViewHelper::question($question, $answers, $employee);

        return Inertia::render('Company/Question/Show', [
            'teams' => TeamCollection::prepare($teams),
            'question' => $answersCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($answers),
        ]);
    }

    /**
     * Get the detail of a given question.
     *
     * @param  Request $request
     * @param  int $companyId
     * @param  int $questionId
     * @param  int $teamId
     *
     * @return JsonResponse
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
            Team::where('company_id', $companyId)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $teams = $company->teams;

        // This is a raw query. Not pretty, but its goal is to grab all employees
        // of the given team who have answered this question. The only way
        // I've found to run this query efficiently is to build this raw query
        // to prevent hydrating a lot of models. This query is really fast.
        $answers = DB::table(DB::raw('answers, teams, employees, employee_team'))
            ->select(DB::raw('distinct employees.id as employee_id, concat(employees.first_name, " ", employees.last_name) as name, teams.id as team_id, answers.body as body, answers.id as answer_id, employees.avatar as avatar, answers.created_at'))
            ->whereRaw('answers.question_id = '.$questionId)
            ->whereRaw('teams.company_id = '.$company->id)
            ->whereRaw('employees.company_id = '.$company->id)
            ->whereRaw('employee_team.team_id = '.$teamId)
            ->whereRaw('teams.id = '.$teamId)
            ->whereRaw('employee_team.employee_id = employees.id')
            ->whereRaw('answers.employee_id = employees.id')
            ->orderBy('answers.created_at', 'desc')
            ->paginate(10);

        $answersCollection = CompanyQuestionViewHelper::teams($question, $answers, $employee);

        return Inertia::render('Company/Question/Show', [
            'teams' => TeamCollection::prepare($teams),
            'currentTeam' => $teamId,
            'question' => $answersCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'paginator' => PaginatorHelper::getData($answers),
        ]);
    }
}
