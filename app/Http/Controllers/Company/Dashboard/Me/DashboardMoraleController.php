<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Morale\LogMorale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardMoraleController extends Controller
{
    /**
     * Create a morale log.
     */
    public function store(Request $request): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'emotion' => $request->input('emotion'),
            'comment' => $request->input('comment'),
        ];

        (new LogMorale)->execute($data);

        $employee->refresh();

        return response()->json([
            'data' => true,
        ], 200);
    }
}
