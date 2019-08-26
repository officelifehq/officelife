<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Company\Employee\Team;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;
use App\Http\Resources\Company\Employee\Employee as EmployeeResource;

class EmployeeTeamController extends Controller
{
    /**
     * Assign a team to the given employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => Auth::user()->id,
            'employee_id' => $employeeId,
            'team_id' => $request->get('id'),
        ];

        $employee = (new AddEmployeeToTeam)->execute($request);

        return new EmployeeResource($employee);
    }

    /**
     * Remove the team for the given employee.
     *
     * @param int $companyId
     * @param int $employeeId
     * @param int $teamId
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $teamId)
    {
        $request = [
            'company_id' => $companyId,
            'author_id' => Auth::user()->id,
            'employee_id' => $employeeId,
            'team_id' => $teamId,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($request);

        return new EmployeeResource($employee);
    }
}
