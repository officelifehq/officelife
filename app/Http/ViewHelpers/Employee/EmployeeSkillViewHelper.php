<?php

namespace App\Http\ViewHelpers\Employee;

use Illuminate\Support\Str;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class EmployeeSkillViewHelper
{
    /**
     * Search all skills matching a given criteria, and filter out the skills
     * already associated with the employee.
     *
     * @param Company $company
     * @param Employee $employee
     * @param string $criteria
     * @return Collection
     */
    public static function search(Company $company, Employee $employee, string $criteria): Collection
    {
        $criteria = Str::of($criteria)->ascii()->lower();

        $potentialSkills = $company->skills()
            ->select('id', 'name')
            ->where('name', 'LIKE', '%'.$criteria.'%')
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();

        $employeeSkills = $employee->skills;

        $potentialSkills = $potentialSkills->diff($employeeSkills);

        $skillsCollection = collect([]);
        foreach ($potentialSkills as $skill) {
            $skillsCollection->push([
                'id' => $skill->id,
                'name' => $skill->name,
            ]);
        }

        return $skillsCollection;
    }
}
