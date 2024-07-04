<?php

namespace App\Http\Controllers\Company\Dashboard\Me;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Worklog\LogWorklog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardWorklogController extends Controller
{
    /**
     * Create a worklog.
     */
    public function store(Request $request): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $company->id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'content' => $request->input('content'),
        ];

        (new LogWorklog)->execute($data);

        $employee->refresh();

        return response()->json([
            'data' => true,
        ], 200);
    }
}
