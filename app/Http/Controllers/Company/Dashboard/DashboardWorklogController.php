<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Models\Company\Company;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardWorklogController extends Controller
{
    /**
     * Create a worklog.
     *
     * @param  Request $request
     * @param int $companyId
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, int $companyId)
    {
        $company = Company::findOrFail($companyId);

        $employee = auth()->user()->getEmployeeObjectForCompany($company);

        $request = [
            'author_id' => auth()->user()->id,
            'employee_id' => $employee->id,
            'content' => $request->content,
        ];

        (new LogWorklog)->execute($request);

        $employee->refresh();

        return new EmployeeResource($employee);
    }
}
