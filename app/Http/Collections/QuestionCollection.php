<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class QuestionCollection
{
    /**
     * Prepare a collection of questions.
     *
     * @param  mixed  $questions
     */
    public static function prepare($questions): Collection
    {
        $questionCollection = collect([]);
        foreach ($questions as $question) {
            $questionCollection->push(
                $question->toObject()
            );
        }

        return $questionCollection;
    }
}
