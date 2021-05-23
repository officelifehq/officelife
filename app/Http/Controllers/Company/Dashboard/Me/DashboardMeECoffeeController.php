<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\ECoffee\MarkECoffeeSessionAsHappened;

class DashboardMeECoffeeController extends Controller
{
    /**
     * Mark an e-coffee match as happened.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $eCoffeeId
     * @param int $eCoffeeMatchId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $eCoffeeId, int $eCoffeeMatchId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'e_coffee_id' => $eCoffeeId,
            'e_coffee_match_id' => $eCoffeeMatchId,
        ];

        (new MarkECoffeeSessionAsHappened)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
