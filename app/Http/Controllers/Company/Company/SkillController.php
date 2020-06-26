<?php

namespace App\Http\Controllers\Company\Company;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Company\Skill;
use App\Helpers\InstanceHelper;
use App\Models\Company\Company;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\CompanySkillViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SkillController extends Controller
{
    /**
     * All the skills in the company, for public use.
     *
     * @return \Inertia\Response
     */
    public function index()
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
     * @param Request $request
     * @param int $companyId
     * @param int $skillId
     *
     * @return JsonResponse
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
}
