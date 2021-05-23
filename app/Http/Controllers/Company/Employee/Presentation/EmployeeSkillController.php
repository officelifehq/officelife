<?php

namespace App\Http\Controllers\Company\Employee\Presentation;

use Illuminate\Http\Request;
use App\Models\Company\Skill;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Employee\EmployeeSkillViewHelper;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;
use App\Services\Company\Employee\Skill\RemoveSkillFromEmployee;

class EmployeeSkillController extends Controller
{
    /**
     * Search an existing skill based on the name.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $employee = Employee::where('company_id', $loggedCompany->id)
            ->findOrFail($employeeId);

        $criteria = $request->input('searchTerm');
        $skills = EmployeeSkillViewHelper::search($loggedCompany, $employee, $criteria);

        return response()->json([
            'data' => $skills,
        ], 200);
    }

    /**
     * Assign a skill to the given employee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $employeeId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'name' => $request->input('searchTerm'),
        ];

        $skill = (new AttachEmployeeToSkill)->execute($data);

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
     * @param int $skillId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $employeeId, int $skillId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'skill_id' => $skillId,
        ];

        (new RemoveSkillFromEmployee)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
