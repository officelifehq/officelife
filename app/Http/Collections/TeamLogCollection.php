<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class TeamLogCollection
{
    /**
     * Prepare a collection of team logs.
     *
     * @param mixed $teamLogs
     *
     * @return Collection
     */
    public static function prepare($teamLogs): Collection
    {
        $teamLogsCollection = collect([]);
        foreach ($teamLogs as $log) {
            $teamLogsCollection->push(
                $log->toObject()
            );
        }

        return $teamLogsCollection;
    }
}
