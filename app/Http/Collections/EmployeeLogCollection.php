<?php

namespace App\Http\Collections;

use Illuminate\Support\Collection;

class EmployeeLogCollection
{
    /**
     * Prepare a collection of employee logs.
     *
     * @param mixed $employeeLogs
     *
     * @return Collection
     */
    public static function prepare($employeeLogs): Collection
    {
        $employeeLogsCollection = collect([]);
        foreach ($employeeLogs as $log) {
            $employeeLogsCollection->push(
                $log->toObject()
            );
        }

        return $employeeLogsCollection;
    }
}
