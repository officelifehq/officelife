<?php

namespace App\Helpers;

use Carbon\Carbon;

class BirthdayHelper
{
    /**
     * Calculate the age of the person's birthdate, relatively to the timezone.
     *
     * @param Carbon  $birthdate Birthdate of the person
     * @param string  $timezone Timezone of the connected user
     * @return int    Age of the person
     */
    public static function age(Carbon $birthdate, string $timezone = null): int
    {
        $now = Carbon::now($timezone);
        $now = Carbon::create($now->year, $now->month, $now->day);

        return $birthdate->diffInYears($now, true);
    }

    /**
     * Indicates whether the given date is a birthday in the next X days.
     * @param Carbon $startDate
     * @param Carbon $birthdate
     * @param int $numberOfDays
     * @return bool
     */
    public static function isBirthdayInXDays(Carbon $startDate, Carbon $birthdate, int $numberOfDays): bool
    {
        $future = $startDate->copy()->addDays($numberOfDays);
        $birthdate->year = $startDate->year;

        if ($birthdate->isPast()) {
            return false;
        }

        return $birthdate->lessThanOrEqualTo($future) && $future->greaterThanOrEqualTo($startDate);
    }

    /**
     * Check if the birthdate will happen in the given range.
     *
     * @param Carbon $birthdate
     * @param Carbon $minDate
     * @param Carbon $maxDate
     * @return bool
     */
    public static function isBirthdayInRange(Carbon $birthdate, Carbon $minDate, Carbon $maxDate): bool
    {
        return $birthdate->between($minDate, $maxDate);
    }
}
