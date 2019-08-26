<?php

namespace App\Http\Controllers\Company\Dashboard;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Employee\Morale\LogMorale;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class DashboardMoraleController extends Controller
{
    /**
     * Create a morale log.
     *
     * @var Request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $employee = InstanceHelper::getLoggedEmployee();

        $request = [
            'author_id' => Auth::user()->id,
            'employee_id' => $employee->id,
            'emotion' => $request->get('emotion'),
            'comment' => $request->get('comment'),
        ];

        (new LogMorale)->execute($request);

        $employee->refresh();

        return new EmployeeResource($employee);
    }
}
