<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Company\CompanyPTOPolicy;

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

    /**
     * Get the number of days in a given year.
     *
     * @param Carbon $date
     * @return int
     */
    public static function daysInYear(Carbon $date) : int
    {
        return $date->isLeapYear() ? 366 : 365;
    }

    /**
     * Return an array containing a yearly calendar.
     * This array contains a row for each month. The first entry in this array
     * is the current month.
     * This is used to populate the PTO policies in the Adminland page.
     *
     * @param CompanyPTOPolicy $ptoPolicy
     * @param string $locale
     * @return array
     */
    public static function prepareCalendar(CompanyPTOPolicy $ptoPolicy, string $locale = 'en') : array
    {
        $calendarDays = $ptoPolicy->calendars()->select('id', 'is_worked', 'day_of_year')->get();
        $firstDayId = $calendarDays->first()->id;

        $date = Carbon::create($ptoPolicy->year);
        $date->setLocale($locale);

        $calendar = [];
        for ($month = 1; $month <= 12; $month++) {
            $currentMonth = collect([]);

            $currentMonth->push([
                'day' => 0,
                'abbreviation' => $date->format('M'),
            ]);

            $daysInMonth = $date->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentMonth->push([
                    'id' => $firstDayId,
                    'day_of_year' => $date->dayOfYear,
                    'day_of_week' => $date->dayOfWeek, // 0: sunday / 6: saturday
                    'abbreviation' => substr($date->format('D'), 0, 1),
                    'is_worked' => $calendarDays->find($firstDayId)->is_worked,
                ]);
                $date->addDay();
                $firstDayId++;
            }

            array_push($calendar, $currentMonth);
        }

        return $calendar;
    }
}
