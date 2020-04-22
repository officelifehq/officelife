<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
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
            'description' => $request->get('description'),
        ];

        $employee = (new SetPersonalDescription)->execute($request);

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
     *
     * @return Response
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $employeeStatusId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new ClearPersonalDescription)->execute($request);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }
}
