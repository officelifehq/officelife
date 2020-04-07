<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class TeamCollection
{
    /**
     * Prepare a collection of teams.
     *
     * @param mixed $teams
     *
     * @return Collection
     */
    public static function prepare($teams): Collection
    {
        $teamCollection = collect([]);
        foreach ($teams as $team) {
            $teamCollection->push(
                $team->toObject()
            );
        }

        return $teamCollection;
    }
}
