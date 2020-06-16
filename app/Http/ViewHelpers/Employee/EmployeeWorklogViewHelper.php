<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class EmployeeWorklogViewHelper
{
    /**
     * Prepare a yearly calendar containing all the days in a year along with the
     * information whether the employee has a worklog for that day or not.
     *
     * @param Collection $worklogs
     * @param int $year
     *
     * @return Collection
     */
    public static function dataForYearlyCalendar(Collection $worklogs, int $year): Collection
    {
        $format = 'Y-m-d';

        $worklogs = $worklogs->filter(function ($log) use ($year) {
            return $log->created_at->year === $year;
        });

        $calendar = collect([]);
        $currentDate = CarbonImmutable::createFromDate($year, 1, 1);
        for ($day = 1; $day <= $currentDate->daysInYear; $day++) {

            // for this date, do we have a worklog?
            $worklog = $worklogs->filter(function ($log) use ($currentDate, $format) {
                return $log->created_at->format($format) === $currentDate->format($format);
            });

            // adding one day as I don't understand why the plugin is off by one day
            $dayAfter = $currentDate;

            $calendar->push([
                'date' => $dayAfter->addDay()->format($format),
                'count' => ($worklog->count() == 1) ? 1 : 0,
            ]);
            $currentDate = $currentDate->addDay();
        }

        return $calendar;
    }

    /**
     * Get a collection representing all the years the employee has logged a
     * worklog for.
     *
     * @param Collection $worklogs
     *
     * @return Collection
     */
    public static function yearsWithEntries(Collection $worklogs): Collection
    {
        $yearsCollection = collect([]);
        foreach ($worklogs as $worklog) {
            $year = Carbon::createFromFormat('Y-m-d H:i:s', $worklog->created_at)->format('Y');
            $yearsCollection->push([
                'number' => intval($year),
            ]);
        }
        $yearsCollection = $yearsCollection->unique()->sortBy(function ($entry) {
            return $entry['number'];
        });

        return $yearsCollection;
    }

    /**
     * Get a collection representing all the months the employee has logged a
     * worklog for, for a given year.
     *
     * @param Collection $worklogs
     * @param int $year
     *
     * @return Collection
     */
    public static function monthsWithEntries(Collection $worklogs, int $year): Collection
    {
        $worklogs = $worklogs->filter(function ($log) use ($year) {
            return $log->created_at->year === $year;
        });

        $monthsCollection = collect([]);

        for ($month = 1; $month < 13; $month++) {
            $logsInMonth = collect([]);
            $date = Carbon::createFromDate($year, $month, 1);

            foreach ($worklogs as $worklog) {
                if ($worklog->created_at->month === $month) {
                    $logsInMonth->push($worklog);
                }
            }

            $monthsCollection->push([
                'month' => $month,
                'occurences' => $logsInMonth->count(),
                'translation' => DateHelper::translateMonth($date),
            ]);
        }

        return $monthsCollection;
    }
}
