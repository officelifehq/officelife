<?php

namespace App\Http\Controllers\Company\Employee\Position;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;
use App\Services\Company\Employee\Position\RemovePositionFromEmployee;

class EmployeePositionController extends Controller
{
    /**
     * Assign a position to the given employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'employee_id' => $employeeId,
            'position_id' => $request->get('id'),
        ];

        $employee = (new AssignPositionToEmployee)->execute($request);

        return new EmployeeResource($employee);
    }

    /**
     * Remove the position for the given employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $companyId, int $employeeId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => auth()->user()->id,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemovePositionFromEmployee)->execute($request);

        return new EmployeeResource($employee);
    }
}
