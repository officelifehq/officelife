<?php

namespace App\Http\Controllers\Company\Employee;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Services\Company\Employee\Description\ClearPersonalDescription;
use App\Services\Company\Employee\Description\SetPersonalDescription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeDescriptionController extends Controller
{
    /**
     * Assign an employee description to the given employee.
     */
    public function store(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'description' => $request->input('description'),
        ];

        $employee = (new SetPersonalDescription)->execute($data);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }

    /**
     * Remove the employee description for the given employee.
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $employeeStatusId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new ClearPersonalDescription)->execute($data);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }
}
