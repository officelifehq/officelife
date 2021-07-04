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
     * @param string|null $criteria
     * @return Collection
     */
    public static function search(Company $company, Employee $employee, ?string $criteria): Collection
    {
        if ($criteria === null) {
            return collect();
        }

        $criteria = Str::of($criteria)->ascii()->lower();

        $employeeSkills = $employee->skills()
            ->select('id')
            ->pluck('id');

        return $company->skills()
            ->select('id', 'name')
            ->where('name', 'LIKE', '%'.$criteria.'%')
            ->whereNotIn('id', $employeeSkills)
            ->orderBy('name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                ];
            });
    }
}
