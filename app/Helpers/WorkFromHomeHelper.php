<?php

namespace App\Helpers;

use App\Models\Company\Employee;
use Carbon\Carbon;

class WorkFromHomeHelper
{
    /**
     * Indicates if the given employee has indicated he worked from home on the
     * given date.
     */
    public static function hasWorkedFromHomeOnDate(Employee $employee, Carbon $date): bool
    {
        $entry = $employee->workFromHomes()->where('date', $date->format('Y-m-d 00:00:00'))->first();

        if ($entry) {
            return true;
        }

        return false;
    }
}
