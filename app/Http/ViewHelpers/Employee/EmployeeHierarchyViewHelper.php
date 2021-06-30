<?php

namespace App\Http\ViewHelpers\Employee;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class EmployeeHierarchyViewHelper
{
    /**
     * Search all employees matching a given criteria.
     *
     * @param Company $company
     * @param Employee $employee
     * @param string|null $criteria
     * @return Collection
     */
    public static function search(Company $company, Employee $employee, ?string $criteria): Collection
    {
        // remove the existing managers of this employee from the list
        $existingManagersForTheEmployee = $employee->getListOfManagers()
            ->pluck('id');

        // remove the existing direct reports of this employee from the list
        $existingDirectReportsForTheEmployee = $employee->getListOfDirectReports()
            ->pluck('id');

        return $company->employees()
            ->select('id', 'first_name', 'last_name')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->where('id', '!=', $employee->id)
            ->whereNotIn('id', $existingManagersForTheEmployee->merge($existingDirectReportsForTheEmployee))
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                ];
            });
    }
}
