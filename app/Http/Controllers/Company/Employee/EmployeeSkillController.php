<?php

namespace App\Http\Controllers\Company\Employee;

use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Models\Company\Skill;
use Illuminate\Http\Response;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use App\Http\Controllers\Controller;
use App\Services\Company\Employee\Skill\AttachEmployeeToSkill;
use App\Services\Company\Employee\Skill\RemoveSkillFromEmployee;

class EmployeeSkillController extends Controller
{
    /**
     * Search an existing skill based on the name.
     *
     * @param Request $request
     * @return Response
     */
    public function search(Request $request)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $name = $request->input('searchTerm');
        $name = StringHelper::removeLettersWithAccent($name);
        $name = strtolower($name);

        $potentialSkills = Skill::search(
            $name,
            $loggedCompany->id,
            10,
            'name desc',
        );

        $collection = collect([]);
        foreach ($potentialSkills as $skill) {
            $collection->push([
                'id' => $skill->id,
                'name' => $skill->name,
                'url' => route('company.skills.show', [
                    'company' => $loggedCompany->id,
                    'skill' => $skill->id,
                ]),
            ]);
        }

        return response()->json([
            'data' => $collection,
        ], 200);
    }

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
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'name' => $request->input('searchTerm'),
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
    public function destroy(Request $request, int $companyId, int $employeeId, int $skillId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $employeeId,
            'skill_id' => $skillId,
        ];

        (new RemoveSkillFromEmployee)->execute($request);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
