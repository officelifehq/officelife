<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    /**
     * Returns a date and the time according to the timezone of the user, in a
     * short format like "Oct 29, 1981 19:32".
     *
     * @param Carbon $date
     * @return string
     */
    public static function getShortDateWithTime($date) : string
    {
        return $date->format(trans('format.short_date_year_time'));
    }

    /**
     * Returns the day and the month in a format like "July 29th".
     *
     * @param Carbon $date
     * @return string
     */
    public static function getLongDayAndMonth($date) : string
    {
        return $date->isoFormat(trans('format.long_month_day'));
    }

    /**
     * Calculate the next occurence in the future for this date.
     *
     * @param Carbon $date
     * @return Carbon
     */
    public static function getNextOccurence(Carbon $date) : Carbon
    {
        if ($date->isFuture()) {
            return $date;
        }

        $date->addYear();

        while ($date->isPast()) {
            $date = static::getNextOccurence($date);
        }

        return $date;
    }
}
