<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class TeamUsefulLinkCollection
{
    /**
     * Prepare a collection of team news.
     *
     * @param mixed $teamUsefulLinks
     *
     * @return Collection
     */
    public static function prepare($teamUsefulLinks): Collection
    {
        $teamUsefulLinksCollection = collect([]);
        foreach ($teamUsefulLinks as $link) {
            $teamUsefulLinksCollection->push(
                $link->toObject()
            );
        }

        return $teamUsefulLinksCollection;
    }
}
