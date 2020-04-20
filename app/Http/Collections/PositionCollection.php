<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class PositionCollection
{
    /**
     * Prepare a collection of positions.
     *
     * @param mixed $positions
     *
     * @return Collection
     */
    public static function prepare($positions): Collection
    {
        $positionCollection = collect([]);
        foreach ($positions as $position) {
            $positionCollection->push(
                $position->toObject()
            );
        }

        return $positionCollection;
    }
}
