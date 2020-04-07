<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class TeamNewsCollection
{
    /**
     * Prepare a collection of team news.
     *
     * @param mixed $teamNews
     *
     * @return Collection
     */
    public static function prepare($teamNews): Collection
    {
        $teamNewsCollection = collect([]);
        foreach ($teamNews as $news) {
            $teamNewsCollection->push(
                $news->toObject()
            );
        }

        return $teamNewsCollection;
    }
}
