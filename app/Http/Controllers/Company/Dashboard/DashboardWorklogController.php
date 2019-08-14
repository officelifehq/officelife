<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardWorklogController extends Controller
{
    /**
     * Create a worklog.
     *
     * @var Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $employee = Cache::get('cachedEmployeeObject');

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
