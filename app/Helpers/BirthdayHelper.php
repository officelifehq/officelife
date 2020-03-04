<?php

namespace App\Helpers;

use Carbon\Carbon;

class BirthdayHelper
{
    /**
     * Indicates whether the given date is a birthday in the next X days.
     */
    public static function isBirthdaySoon(Carbon $date, int $numberOfDays): bool
    {
        return $date->format(trans('format.date'));
    }
}
