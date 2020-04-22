<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;
use App\Services\Company\Employee\Position\RemovePositionFromEmployee;

class EmployeePositionController extends Controller
{
    /**
     * Assign a position to the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'position_id' => $request->get('id'),
        ];

        $employee = (new AssignPositionToEmployee)->execute($request);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'position' => ($employee->position) ? $employee->position->title : null,
            ],
        ], 200);
    }

    /**
     * Remove the position for the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function destroy(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemovePositionFromEmployee)->execute($request);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }
}
