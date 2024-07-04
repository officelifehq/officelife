<?php

namespace App\Http\Controllers\Company\Adminland;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Collections\QuestionCollection;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\Question\ActivateQuestion;
use App\Services\Company\Adminland\Question\CreateQuestion;
use App\Services\Company\Adminland\Question\DeactivateQuestion;
use App\Services\Company\Adminland\Question\DestroyQuestion;
use App\Services\Company\Adminland\Question\UpdateQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminQuestionController extends Controller
{
    /**
     * Show the list of questions.
     */
    public function index(): Response
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
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'title' => $request->input('title'),
            'active' => false,
        ];

        $question = (new CreateQuestion)->execute($data);

        return response()->json([
            'data' => $question->toObject(),
        ], 201);
    }

    /**
     * Update the question.
     */
    public function update(Request $request, int $companyId, int $questionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'question_id' => $questionId,
            'title' => $request->input('title'),
            'active' => $request->input('active'),
        ];

        $question = (new UpdateQuestion)->execute($data);

        return response()->json([
            'data' => $question->toObject(),
        ], 200);
    }

    /**
     * Delete the question.
     */
    public function destroy(Request $request, int $companyId, int $questionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'question_id' => $questionId,
            'author_id' => $loggedEmployee->id,
        ];

        (new DestroyQuestion)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Activate the question.
     */
    public function activate(Request $request, int $companyId, int $questionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'question_id' => $questionId,
        ];

        (new ActivateQuestion)->execute($data);

        $company = InstanceHelper::getLoggedCompany();
        $questions = $company->questions()->get();
        $questionsCollection = QuestionCollection::prepare($questions);

        return response()->json([
            'data' => $questionsCollection,
        ], 200);
    }

    /**
     * Deactivate the question.
     */
    public function deactivate(Request $request, int $companyId, int $questionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'question_id' => $questionId,
        ];

        (new DeactivateQuestion)->execute($data);

        $company = InstanceHelper::getLoggedCompany();
        $questions = $company->questions()->get();
        $questionsCollection = QuestionCollection::prepare($questions);

        return response()->json([
            'data' => $questionsCollection,
        ], 200);
    }
}
