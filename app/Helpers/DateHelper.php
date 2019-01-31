<?php

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class DateHelper
{
    /**
     * Returns a date and the time according to the timezone of the user, in a
     * short format like "Oct 29, 1981 19:32".
     *
     * @param Carbon $date
     * @return string
     */
    public static function getShortDateWithTime($date)
    {
        return $date->format(trans('format.short_date_year_time'));
    }
}
