<?php

namespace App\Helpers;

use App\Models\Company\CompanyPTOPolicy;
use Carbon\Carbon;

class DateHelper
{
    /**
     * Return a date according to the timezone of the user, in a
     * short format like "Oct 29, 1981".
     */
    public static function formatDate(Carbon $date, ?string $timezone = null): string
    {
        if ($timezone) {
            $date->setTimezone($timezone);
        }

        return $date->isoFormat(trans('format.date'));
    }

    /**
     * Return a date and the time according to the timezone of the user, in a
     * short format like "Oct 29, 1981 19:32".
     */
    public static function formatShortDateWithTime(Carbon $date, ?string $timezone = null): string
    {
        if ($timezone) {
            $date->setTimezone($timezone);
        }

        return $date->isoFormat(trans('format.short_date_year_time'));
    }

    /**
     * Return the day and the month in a format like "July 29th".
     */
    public static function formatMonthAndDay(Carbon $date): string
    {
        return $date->isoFormat(trans('format.long_month_day'));
    }

    /**
     * Return the short month and the year in a format like "Jul 2020".
     */
    public static function formatMonthAndYear(Carbon $date): string
    {
        return $date->isoFormat(trans('format.short_month_day'));
    }

    /**
     * Return the day and the month in a format like "Jul 29".
     */
    public static function formatShortMonthAndDay(Carbon $date): string
    {
        return $date->isoFormat(trans('format.short_date'));
    }

    /**
     * Return the day and the month in a format like "Monday (July 29th)".
     */
    public static function formatDayAndMonthInParenthesis(Carbon $date, ?string $timezone = null): string
    {
        if ($timezone) {
            $date->setTimezone($timezone);
        }

        return $date->isoFormat(trans('format.day_month_parenthesis'));
    }

    /**
     * Return the complete date like "Monday, July 29th 2020".
     */
    public static function formatFullDate(Carbon $date): string
    {
        return $date->isoFormat(trans('format.full_date'));
    }

    /**
     * Translate the given month to a string using the locale of the app.
     */
    public static function translateMonth(Carbon $date): string
    {
        return $date->isoFormat(trans('format.full_month'));
    }

    /**
     * Return the day as a string like "Wednesday".
     */
    public static function day(Carbon $date): string
    {
        return $date->isoFormat(trans('format.day'));
    }

    /**
     * Return the day as a string like "Jul. 29th".
     */
    public static function dayWithShortMonth(Carbon $date): string
    {
        return $date->isoFormat(trans('format.day_short_month'));
    }

    /**
     * Calculate the next occurence in the future for this date.
     */
    public static function getNextOccurence(Carbon $date): Carbon
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
     */
    public static function getNumberOfDaysInYear(Carbon $date): int
    {
        return $date->isLeapYear() ? 366 : 365;
    }

    /**
     * Determine if the date is in the future, in the present or in the past.
     */
    public static function determineDateStatus(Carbon $date): string
    {
        $status = '';
        if ($date->isFuture()) {
            $status = 'future';
        } else {
            if ($date->isCurrentDay()) {
                $status = 'current';
            } else {
                $status = 'past';
            }
        }

        return $status;
    }

    /**
     * Return a string indicating the number of days or hours left, like
     * `3 hours left` or `1 day left`, depending on the given date.
     */
    public static function hoursOrDaysLeft(Carbon $date): string
    {
        $now = Carbon::now();
        $hoursLeft = $now->diffInHours($date);

        if ($hoursLeft < 0) {
            $hoursLeft = $hoursLeft * -1;
        }

        if ($hoursLeft < 24) {
            $timeLeft = trans_choice('app.hours_left', floor($hoursLeft));
        } else {
            $days = $now->diffInDays($date);
            if ($days < 0) {
                $days = $days * -1;
            }

            $timeLeft = trans_choice('app.days_left', floor($days));
        }

        return $timeLeft;
    }

    /**
     * Return an array containing a yearly calendar.
     * This array contains a row for each month. The first entry in this array
     * is the current month.
     * This is used to populate the PTO policies in the Adminland page.
     */
    public static function prepareCalendar(CompanyPTOPolicy $ptoPolicy, string $locale = 'en'): array
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
                'abbreviation' => $date->isoFormat('MMM'),
            ]);

            $daysInMonth = $date->daysInMonth;
            for ($day = 1; $day <= $daysInMonth; $day++) {
                $currentMonth->push([
                    'id' => $firstDayId,
                    'day_of_year' => $date->dayOfYear,
                    'day_of_week' => $date->dayOfWeek, // 0: sunday / 6: saturday
                    'abbreviation' => mb_substr($date->isoFormat('dd'), 0, 1),
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
