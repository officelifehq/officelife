<?php

namespace App\Http\Controllers\Company\Employee;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Models\Company\Employee;
use App\Services\Company\Employee\EmployeeStatus\AssignEmployeeStatusToEmployee;
use App\Services\Company\Employee\EmployeeStatus\RemoveEmployeeStatusFromEmployee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeStatusController extends Controller
{
    /**
     * Return the list of employee statuses in the company.
     */
    public function index(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $statuses = EmployeeShowViewHelper::employeeStatuses($loggedCompany);

        return response()->json([
            'data' => $statuses,
        ], 200);
    }

    /**
     * Assign an employee status to the given employee.
     */
    public function store(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'employee_status_id' => $request->input('id'),
        ];

        $employee = (new AssignEmployeeStatusToEmployee)->execute($data);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }

    /**
     * Remove the employee status for the given employee.
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $employeeStatusId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemoveEmployeeStatusFromEmployee)->execute($data);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }
}
