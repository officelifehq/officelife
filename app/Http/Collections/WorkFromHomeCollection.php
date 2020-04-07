<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class WorkFromHomeCollection
{
    /**
     * Prepare a collection of work from home.
     *
     * @param mixed $entries
     *
     * @return Collection
     */
    public static function prepare($entries): Collection
    {
        $entriesCollection = collect([]);
        foreach ($entries as $entry) {
            $entriesCollection->push(
                $entry->toObject()
            );
        }

        return $entriesCollection;
    }
}
