<?php

namespace App\Http\Controllers\Company\Employee;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Models\Company\Employee;
use App\Services\Company\Employee\Team;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class EmployeeTeamController extends Controller
{
    /**
     * Return the list of teams in the company.
     */
    public function index(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        // all teams in company
        $teams = $loggedCompany->teams()->with('leader')->get();
        $teams = EmployeeShowViewHelper::teams($teams, $loggedCompany);

        return response()->json([
            'data' => $teams,
        ], 200);
    }

    /**
     * Assign a team to the given employee.
     *
     * @return Collection
     */
    public function store(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'team_id' => $request->input('id'),
        ];

        $employee = (new AddEmployeeToTeam)->execute($data);

        return EmployeeShowViewHelper::teams($employee->teams, $loggedCompany);
    }

    /**
     * Remove the team for the given employee.
     *
     * @return Collection
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $teamId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'team_id' => $teamId,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($data);

        return EmployeeShowViewHelper::teams($employee->teams, $loggedCompany);
    }
}
