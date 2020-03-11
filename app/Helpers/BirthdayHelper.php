<?php

namespace App\Helpers;

use Carbon\Carbon;

class BirthdayHelper
{
    /**
     * Indicates whether the given date is a birthday in the next X days.
     */
    public static function isBirthdaySoon(Carbon $startDate, Carbon $birthdate, int $numberOfDays): bool
    {
        $future = $startDate->addDays($numberOfDays);
        $birthdate->year = $startDate->year;

        if ($birthdate->isPast()) {
            return false;
        }

        return $birthdate->lessThanOrEqualTo($future) && $future->greaterThanOrEqualTo($startDate);
    }
}
