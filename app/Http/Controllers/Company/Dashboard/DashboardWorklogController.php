<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardWorklogController extends Controller
{
    /**
     * Create a worklog.
     *
     * @var Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'content' => $request->content,
        ];

        (new LogWorklog)->execute($request);

        $employee->refresh();

        return new EmployeeResource($employee);
    }
}
