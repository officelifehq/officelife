<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class WorklogCollection
{
    /**
     * Prepare a collection of worklogs.
     *
     * @param mixed $worklogs
     *
     * @return Collection
     */
    public static function prepare($worklogs): Collection
    {
        $worklogsCollection = collect([]);
        foreach ($worklogs as $worklog) {
            $worklogsCollection->push(
                $worklog->toObject()
            );
        }

        return $worklogsCollection;
    }
}
