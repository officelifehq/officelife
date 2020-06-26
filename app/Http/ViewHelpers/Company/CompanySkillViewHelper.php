<?php

namespace App\Http\ViewHelpers\Company;

use App\Models\Company\Skill;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class CompanySkillViewHelper
{
    /**
     * Array containing all the information about the skills.
     *
     * @param Company $company
     *
     * @return Collection|null
     */
    public static function skills(Company $company): ?Collection
    {
        // get all skills
        $skills = $company->skills()->with('employees')->orderBy('skills.name', 'asc')->get();

        // building a collection of questions
        $skillCollection = collect([]);
        foreach ($skills as $skill) {
            $numberOfEmployees = $skill->employees->count();

            $skillCollection->push([
                'id' => $skill->id,
                'name' => $skill->name,
                'number_of_employees' => $numberOfEmployees,
                'url' => route('company.skills.show', [
                    'company' => $company,
                    'skill' => $skill,
                ]),
            ]);
        }

        return $skillCollection;
    }

    /**
     * Array containing all the information about a given skill.
     *
     * @param Skill $company
     *
     * @return Collection|null
     */
    public static function skill(Skill $skill): ?Collection
    {
        $employees = $skill->employees
            ->with('position')
            ->with('team')
            ->orderBy('name', 'asc')->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
                'position' => (! $employee->position) ? null : [
                    'title' => $employee->position->title,
                ],
                'team' => (! $employee->team) ? null : [
                    'name' => $employee->team->name,
                ],
                'url' => route('employees.show', [
                    'company' => $employee->company,
                    'employee' => $employee,
                ]),
                'url_team' => route('team.show', [
                    'company' => $employee->company,
                    'team' => $employee->team,
                ]),
            ]);
        }

        return $employeesCollection;
    }
}
