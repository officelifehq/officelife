<?php

namespace App\Http\ViewHelpers\Company\Group;

use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class GroupCreateViewHelper
{
    /**
     * Search all potential members for the group.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function search(Company $company, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
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
        foreach ($potentialEmployees as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 23),
            ]);
        }

        return $employeesCollection;
    }
}
