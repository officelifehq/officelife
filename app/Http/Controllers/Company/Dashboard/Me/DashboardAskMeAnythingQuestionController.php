<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingQuestion;

class DashboardAskMeAnythingQuestionController extends Controller
{
    /**
     * Submit a question.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $sessionId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $sessionId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'ask_me_anything_session_id' => $sessionId,
            'question' => $request->input('question'),
            'anonymous' => $request->input('anonymous'),
        ];

        (new CreateAskMeAnythingQuestion)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }
}
