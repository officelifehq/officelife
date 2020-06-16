<?php

namespace App\Http\ViewHelpers\Team;

use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class TeamMembersViewHelper
{
    /**
     * Array containing all the basic information about employees that the user
     * has made a search about.
     *
     * @param Collection $employees
     *
     * @return Collection
     */
    public static function searchedEmployees(Collection $employees): Collection
    {
        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }

    /**
     * Array containing all the information about a specific employee.
     *
     * @param Employee $employee
     *
     * @return array
     */
    public static function employee(Employee $employee): array
    {
        return [
            'id' => $employee->id,
            'name' => $employee->name,
            'avatar' => $employee->avatar,
            'position' => $employee->position,
        ];
    }
}
