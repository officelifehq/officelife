<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Adminland\AskMeAnything\CreateAskMeAnythingQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardAskMeAnythingQuestionController extends Controller
{
    /**
     * Submit a question.
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
