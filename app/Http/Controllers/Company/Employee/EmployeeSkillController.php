<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Position\AssignPositionToEmployee;
use App\Services\Company\Employee\Position\RemovePositionFromEmployee;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;

class EmployeeSkillController extends Controller
{
    /**
     * Assign a skill to the given employee.
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
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'skill_id' => $request->input('id'),
        ];

        $skill = (new AttachEmployeeToSkill)->execute($request);

        return response()->json([
            'data' => [
                'id' => $skill->id,
                'name' => $skill->name,
                'url' => route('company.skills.show', [
                    'company' => $loggedCompany->id,
                    'skill' => $skill->id,
                ]),
            ],
        ], 200);
    }

    /**
     * Remove the skill from the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     *
     * @return Response
     */
    public function destroy(Request $request, int $companyId, int $employeeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'skill_id' => $request->input('id'),
        ];

        $employee = (new )->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
