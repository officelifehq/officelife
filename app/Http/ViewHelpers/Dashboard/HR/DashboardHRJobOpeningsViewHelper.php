<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

use App\Helpers\ImageHelper;
use App\Models\Company\Company;
use Illuminate\Support\Collection;

class DashboardHRJobOpeningsViewHelper
{
    /**
     * Get all the positions in the company.
     *
     * @param Company $company
     * @return Collection|null
     */
    public static function positions(Company $company): ?Collection
    {
        $positions = $company->positions()->orderBy('title')->get();

        $positionsCollection = collect();
        foreach ($positions as $position) {
            $positionsCollection->push([
                'value' => $position->id,
                'label' => $position->title,
            ]);
        }

        return $positionsCollection;
    }

    /**
     * Get all the potential sponsors in the company.
     *
     * @param Company $company
     * @param string $criteria
     * @return Collection|null
     */
    public static function potentialSponsors(Company $company, string $criteria): ?Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $criteria . '%')
                    ->orWhere('email', 'LIKE', '%' . $criteria . '%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $potentialEmployeesCollection = collect([]);
        foreach ($potentialEmployees as $potential) {
            $potentialEmployeesCollection->push([
                'id' => $potential->id,
                'name' => $potential->name,
                'avatar' => ImageHelper::getAvatar($potential, 64),
            ]);
        }

        return $potentialEmployeesCollection;
    }
}
