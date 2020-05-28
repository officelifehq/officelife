<?php

namespace App\Http\Controllers\Company\Adminland;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\Collections\QuestionCollection;
use App\Services\Company\Adminland\Question\CreateQuestion;
use App\Services\Company\Adminland\Question\UpdateQuestion;
use App\Services\Company\Adminland\Question\DestroyQuestion;
use App\Services\Company\Adminland\Question\ActivateQuestion;
use App\Services\Company\Adminland\Question\DeactivateQuestion;

class AdminQuestionController extends Controller
{
    /**
     * Show the list of questions.
     *
     * @return Response
     */
    public function index()
    {
        $company = InstanceHelper::getLoggedCompany();
        $questions = $company->questions()->get();

        $questionsCollection = QuestionCollection::prepare($questions);

        return Inertia::render('Adminland/Question/Index', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
            'questions' => $questionsCollection,
        ]);
    }

    /**
     * Create the question.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function store(Request $request, int $companyId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->input('title'),
            'active' => false,
        ];

        $question = (new CreateQuestion)->execute($request);

        return response()->json([
            'data' => $question->toObject(),
        ], 201);
    }

    /**
     * Update the question.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $questionId
     *
     * @return Response
     */
    public function update(Request $request, int $companyId, int $questionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'question_id' => $questionId,
            'title' => $request->input('title'),
            'active' => $request->input('active'),
        ];

        $question = (new UpdateQuestion)->execute($request);

        return response()->json([
            'data' => $question->toObject(),
        ], 200);
    }

    /**
     * Delete the question.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $questionId
     *
     * @return Response
     */
    public function destroy(Request $request, int $companyId, int $questionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'question_id' => $questionId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyQuestion)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Activate the question.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $questionId
     *
     * @return Response
     */
    public function activate(Request $request, int $companyId, int $questionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'question_id' => $questionId,
        ];

        (new ActivateQuestion)->execute($request);

        $company = InstanceHelper::getLoggedCompany();
        $questions = $company->questions()->get();
        $questionsCollection = QuestionCollection::prepare($questions);

        return response()->json([
            'data' => $questionsCollection,
        ], 200);
    }

    /**
     * Deactivate the question.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $questionId
     *
     * @return Response
     */
    public function deactivate(Request $request, int $companyId, int $questionId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'question_id' => $questionId,
        ];

        (new DeactivateQuestion)->execute($request);

        $company = InstanceHelper::getLoggedCompany();
        $questions = $company->questions()->get();
        $questionsCollection = QuestionCollection::prepare($questions);

        return response()->json([
            'data' => $questionsCollection,
        ], 200);
    }
}
