<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Company;
use Illuminate\Support\Collection;

class AdminPositionViewHelper
{
    /**
     * Collection containing all the positions in the company.
     *
     * @param mixed $company
     * @return Collection|null
     */
    public static function list($company): ?Collection
    {
        $positions = $company->positions()->orderBy('title', 'asc')->get();
        $positionCollection = collect([]);
        foreach ($positions as $position) {
            $positionCollection->push([
                'id' => $position->id,
                'title' => $position->title,
            ]);
        }

        return $positionCollection;
    }
}
