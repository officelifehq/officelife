<?php

namespace App\Http\ViewHelpers\Company;

use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class HeaderSearchViewHelper
{
    /**
     * Get all the employees matching a given criteria.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function employees(Company $company, string $criteria): Collection
    {
        $employees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->with('picture')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee),
            ]);
        }

        return $employeesCollection;
    }

    /**
     * Get all the teams matching a given criteria.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function teams(Company $company, string $criteria): Collection
    {
        $teams = $company->teams()
            ->select('id', 'name')
            ->where('name', 'LIKE', '%'.$criteria.'%')
            ->orderBy('name', 'asc')
            ->take(10)
            ->get();

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
