<?php

namespace App\Http\ViewHelpers\Dashboard\HR;

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
}
