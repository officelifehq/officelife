<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Description\SetPersonalDescription;
use App\Services\Company\Employee\Description\ClearPersonalDescription;

class EmployeeDescriptionController extends Controller
{
    /**
     * Assign an employee description to the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
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
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @param int $employeeStatusId
     * @return JsonResponse
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
