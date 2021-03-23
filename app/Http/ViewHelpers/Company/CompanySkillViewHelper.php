<?php

namespace App\Http\ViewHelpers\Company;

use App\Helpers\ImageHelper;
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
        $skills = $company->skills()
            ->with('employees')
            ->orderBy('skills.name', 'asc')
            ->get();

        $skillCollection = collect([]);
        foreach ($skills as $skill) {
            // remove employees who are locked
            $employees = $skill->employees->filter(function ($employee) {
                return ! $employee->locked;
            });

            $numberOfEmployees = $employees->count();

            // if this equals 0, that means we have removed all locked employees
            // and we shouldn't display this skill
            if ($numberOfEmployees != 0) {
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
        }

        return $skillCollection;
    }

    /**
     * Array containing all the information about the employees who have a given
     * skill.
     *
     * @param Skill $skill
     * @return Collection|null
     */
    public static function employeesWithSkill(Skill $skill): ?Collection
    {
        $employees = $skill->employees()
            ->with('position')
            ->with('teams')
            ->with('skills')
            ->get();

        // remove employees who are locked
        $employees = $skill->employees->filter(function ($employee) {
            return ! $employee->locked;
        });

        $company = $skill->company;

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $teamsCollection = collect([]);
            foreach ($employee->teams as $team) {
                $teamsCollection->push([
                    'id' => $team->id,
                    'name' => $team->name,
                    'url' => route('team.show', [
                        'company' => $company,
                        'team' => $team,
                    ]),
                ]);
            }

            $skillsCollection = collect([]);
            foreach ($employee->skills as $uniqueSkill) {
                if ($skill->id != $uniqueSkill->id) {
                    $skillsCollection->push([
                        'id' => $uniqueSkill->id,
                        'name' => $uniqueSkill->name,
                        'url' => route('company.skills.show', [
                            'company' => $company,
                            'skill' => $uniqueSkill,
                        ]),
                    ]);
                }
            }

            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 65),
                'position' => (! $employee->position) ? null : [
                    'title' => $employee->position->title,
                ],
                'skills' => $skillsCollection,
                'teams' => $teamsCollection,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return $employeesCollection->sortBy('name');
    }
}
