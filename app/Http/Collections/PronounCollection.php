<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class PronounCollection
{
    /**
     * Prepare a collection of pronouns.
     *
     * @param mixed $pronouns
     *
     * @return Collection
     */
    public static function prepare($pronouns): Collection
    {
        $pronounCollection = collect([]);
        foreach ($pronouns as $pronoun) {
            $pronounCollection->push(
                $pronoun->toObject()
            );
        }

        return $pronounCollection;
    }
}
