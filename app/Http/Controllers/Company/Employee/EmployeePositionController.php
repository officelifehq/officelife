<?php

namespace App\Http\Controllers\Company\Employee;

use App\Helpers\ImageHelper;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;
use App\Services\Company\Employee\Position\RemovePositionFromEmployee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeePositionController extends Controller
{
    /**
     * Assign a position to the given employee.
     */
    public function store(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'position_id' => $request->input('id'),
        ];

        $employee = (new AssignPositionToEmployee)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee),
                'position' => ($employee->position) ? $employee->position : null,
            ],
        ], 200);
    }

    /**
     * Remove the position for the given employee.
     */
    public function destroy(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemovePositionFromEmployee)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee),
                'position' => null,
            ],
        ], 200);
    }
}
