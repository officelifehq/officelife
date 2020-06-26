<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use App\Helpers\PaginatorHelper;
use App\Models\Company\Question;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanySkillViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\CompanyQuestionViewHelper;

class SkillController extends Controller
{
    /**
     * All the skills in the company, for public use.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();

        $skillsCollection = CompanySkillViewHelper::skills($company);

        return Inertia::render('Company/Skill/Index', [
            'skills' => $skillsCollection,
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
}
