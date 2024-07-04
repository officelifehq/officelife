<?php

namespace App\Http\Controllers\Company\Company;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanySkillViewHelper;
use App\Models\Company\Company;
use App\Models\Company\Skill;
use App\Services\Company\Employee\Skill\DestroySkill;
use App\Services\Company\Employee\Skill\UpdateSkill;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SkillController extends Controller
{
    /**
     * All the skills in the company, for public use.
     */
    public function index(): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        $skillsCollection = CompanySkillViewHelper::skills($company);

        return Inertia::render('Company/Skill/Index', [
            'skills' => $skillsCollection,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Get the detail of a given skill.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, int $skillId)
    {
        $company = InstanceHelper::getLoggedCompany();

        // make sure the skill belongs to the company
        try {
            $skill = Skill::where('company_id', $company->id)
                ->findOrFail($skillId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $employees = CompanySkillViewHelper::employeesWithSkill($skill);

        return Inertia::render('Company/Skill/Show', [
            'skill' => $skill,
            'employees' => $employees,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Update the skill.
     */
    public function update(Request $request, int $companyId, int $skillId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'skill_id' => $skillId,
            'name' => $request->input('name'),
        ];

        (new UpdateSkill)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Delete the skill.
     */
    public function destroy(Request $request, int $companyId, int $skillId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $data = [
            'company_id' => $companyId,
            'author_id' => $loggedEmployee->id,
            'skill_id' => $skillId,
        ];

        (new DestroySkill)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
