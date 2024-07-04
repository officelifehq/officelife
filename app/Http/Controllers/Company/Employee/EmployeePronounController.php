<?php

namespace App\Http\Controllers\Company\Employee;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\Employee;
use App\Services\Company\Employee\Pronoun\AssignPronounToEmployee;
use App\Services\Company\Employee\Pronoun\RemovePronounFromEmployee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeePronounController extends Controller
{
    /**
     * Assign an employee gender pronoun to the given employee.
     */
    public function store(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'pronoun_id' => $request->input('id'),
        ];

        $employee = (new AssignPronounToEmployee)->execute($data);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }

    /**
     * Remove the employee gender pronoun for the given employee.
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $employeeStatusId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemovePronounFromEmployee)->execute($data);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }
}
