<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class AnswerCollection
{
    /**
     * Prepare a collection of answers.
     *
     * @param mixed $answers
     *
     * @return Collection
     */
    public static function prepare($answers): Collection
    {
        $answerCollection = collect([]);
        foreach ($answers as $answer) {
            $answerCollection->push(
                $answer->toObject()
            );
        }

        return $answerCollection;
    }
}
