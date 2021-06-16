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
     * @param string|null $criteria
     * @return Collection
     */
    public static function employees(Company $company, ?string $criteria): Collection
    {
        if ($criteria === null) {
            return collect();
        }

        return $company->employees()
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
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee),
                ];
            });
    }

    /**
     * Get all the teams matching a given criteria.
     *
     * @param Company $company
     * @param string|null $criteria
     * @return Collection
     */
    public static function teams(Company $company, ?string $criteria): Collection
    {
        if ($criteria === null) {
            return collect();
        }

        return $company->teams()
            ->select('id', 'name')
            ->where('name', 'LIKE', '%'.$criteria.'%')
            ->orderBy('name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($team) {
                return [
                    'id' => $team->id,
                    'name' => $team->name,
                ];
            });
    }
}
