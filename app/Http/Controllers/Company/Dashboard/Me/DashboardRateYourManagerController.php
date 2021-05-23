<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\RateYourManager\RateYourManager;
use App\Services\Company\Employee\RateYourManager\AddCommentToRatingAboutManager;

class DashboardRateYourManagerController extends Controller
{
    /**
     * Store the answer of the Rate your manager survey.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $answerId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $answerId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'answer_id' => $answerId,
            'rating' => $request->input('rating'),
        ];

        $answer = (new RateYourManager)->execute($data);

        return response()->json([
            'data' => $answer->id,
        ], 201);
    }

    /**
     * Store the comment about the answer of the Rate your manager survey.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $answerId
     * @return JsonResponse
     */
    public function storeComment(Request $request, int $companyId, int $answerId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'answer_id' => $answerId,
            'comment' => $request->input('comment'),
            'reveal_identity_to_manager' => $request->input('reveal'),
        ];

        $answer = (new AddCommentToRatingAboutManager)->execute($data);

        return response()->json([
            'data' => $answer->id,
        ], 201);
    }
}
