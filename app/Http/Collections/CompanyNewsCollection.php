<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class CompanyNewsCollection
{
    /**
     * Prepare a collection of company news.
     *
     * @param  mixed  $companyNews
     */
    public static function prepare($companyNews): Collection
    {
        $companyNewsCollection = collect([]);
        foreach ($companyNews as $news) {
            $companyNewsCollection->push(
                $news->toObject()
            );
        }

        return $companyNewsCollection;
    }
}
