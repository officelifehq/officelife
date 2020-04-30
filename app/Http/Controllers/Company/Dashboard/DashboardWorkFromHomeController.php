<?php

namespace App\Http\Controllers\Company\Dashboard;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\WorkFromHome\UpdateWorkFromHomeInformation;

class DashboardWorkFromHomeController extends Controller
{
    /**
     * Change the status of Work from home.
     *
     * @var Request
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'date' => Carbon::now()->format('Y-m-d'),
            'work_from_home' => $request->input('content'),
        ];

        (new UpdateWorkFromHomeInformation)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
