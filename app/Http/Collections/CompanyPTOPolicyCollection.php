<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class CompanyPTOPolicyCollection
{
    /**
     * Prepare a collection of company news.
     *
     * @param mixed $statuses
     *
     * @return Collection
     */
    public static function prepare($statuses): Collection
    {
        $statusCollection = collect([]);
        foreach ($statuses as $status) {
            $statusCollection->push(
                $status->toObject()
            );
        }

        return $statusCollection;
    }
}
