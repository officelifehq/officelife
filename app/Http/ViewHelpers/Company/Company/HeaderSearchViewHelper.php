<?php

namespace App\Http\ViewHelpers\Company\Company;

use Illuminate\Support\Collection;

class HeaderSearchViewHelper
{
    /**
     * Array containing information about the employees.
     *
     * @param mixed $employees
     *
     * @return Collection
     */
    public static function employees($employees): Collection
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
     * Array containing information about the teams.
     *
     * @param mixed $teams
     *
     * @return Collection
     */
    public static function teams($teams): Collection
    {
        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
            ]);
        }

        return $teamsCollection;
    }
}
