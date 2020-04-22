<?php

namespace App\Http\Controllers\Company\Team;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;
use App\Http\Resources\Company\Employee\EmployeeListWithoutTeams as EmployeeResource;

class TeamMembersController extends Controller
{
    /**
     * Search members that can be added to the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, int $companyId, int $teamId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $company->id)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $search = $request->get('searchTerm');
        $potentialEmployees = Employee::search(
            $search,
            $companyId,
            10,
            'created_at desc'
        );

        // remove the existing team members from the list
        $existingMembers = $team->employees;
        $potentialEmployees = $potentialEmployees->diff($existingMembers);

        return EmployeeResource::collection($potentialEmployees);
    }

    /**
     * Add the employee to the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $employeeId
     *
     * @return \Illuminate\Http\Response
     */
    public function attach(Request $request, int $companyId, int $teamId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $employeeId,
        ];

        $employee = (new AddEmployeeToTeam)->execute($request);

        return new EmployeeResource($employee);
    }

    /**
     * Remove the employee from the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $employeeId
     *
     * @return \Illuminate\Http\Response
     */
    public function detach(Request $request, int $companyId, int $teamId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($request);

        return new EmployeeResource($employee);
    }
}
