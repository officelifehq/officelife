<?php

namespace App\Http\Controllers\Company\Team;

use App\Models\Company\Team;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Team\TeamMembersViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Employee\Team\RemoveEmployeeFromTeam;

class TeamMembersController extends Controller
{
    /**
     * Search members that can be added to the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @return JsonResponse
     */
    public function index(Request $request, int $companyId, int $teamId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $team = Team::where('company_id', $company->id)
                ->findOrFail($teamId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $search = $request->input('searchTerm');
        $potentialEmployees = Employee::search(
            $search,
            $companyId,
            10,
            'created_at desc',
            'and locked = false',
            'position'
        );

        // remove the existing team members from the list
        $existingMembers = $team->employees;
        $potentialEmployees = $potentialEmployees->diff($existingMembers);

        return response()->json([
            'data' => TeamMembersViewHelper::searchedEmployees($potentialEmployees),
        ], 200);
    }

    /**
     * Add the employee to the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function attach(Request $request, int $companyId, int $teamId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $employeeId,
        ];

        $employee = (new AddEmployeeToTeam)->execute($request);

        return response()->json([
            'data' => TeamMembersViewHelper::employee($employee),
        ], 200);
    }

    /**
     * Remove the employee from the team.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $teamId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function detach(Request $request, int $companyId, int $teamId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'team_id' => $teamId,
            'employee_id' => $employeeId,
        ];

        $employee = (new RemoveEmployeeFromTeam)->execute($request);

        return response()->json([
            'data' => TeamMembersViewHelper::employee($employee),
        ], 200);
    }
}
