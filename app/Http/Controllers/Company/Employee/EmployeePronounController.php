<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Pronoun\AssignPronounToEmployee;
use App\Services\Company\Employee\Pronoun\RemovePronounFromEmployee;

class EmployeePronounController extends Controller
{
    /**
     * Assign an employee gender pronoun to the given employee.
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
            'pronoun_id' => $request->get('id'),
        ];

        $employee = (new AssignPronounToEmployee)->execute($request);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }

    /**
     * Remove the employee gender pronoun for the given employee.
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

        $employee = (new RemovePronounFromEmployee)->execute($request);

        return response()->json([
            'data' => $employee->toObject(),
        ], 200);
    }
}
