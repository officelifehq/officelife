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
     * @param string $criteria
     * @return Collection
     */
    public static function search(Company $company, Employee $employee, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->where('id', '!=', $employee->id)
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        // remove the existing managers of this employee from the list
        $existingManagersForTheEmployee = $employee->getListOfManagers();
        $potentialEmployees = $potentialEmployees->diff($existingManagersForTheEmployee);

        // remove the existing direct reports of this employee from the list
        $existingDirectReportsForTheEmployee = $employee->getListOfDirectReports();
        $potentialEmployees = $potentialEmployees->diff($existingDirectReportsForTheEmployee);

        $employeesCollection = collect([]);
        foreach ($potentialEmployees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }
}
