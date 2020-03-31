<?php

namespace App\Helpers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use App\Models\Company\Team;
use App\Models\Company\Morale;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class WorklogHelper
{
    /**
     * Creates an array containing all the information regarding the worklogs
     * logged on a specific day for a specific team.
     * Used on the team page and the team dashboard page.
     *
     * This array also contains an indicator telling how many team members have
     * filled the worklogs for the day. The rules are as follow:
     * - less than 20% of team members have filled the worklogs: red
     * - 20% -> 80%: yellow
     * - > 80%: green
     */
    public static function getInformationAboutTeam(Team $team, Carbon $date): array
    {
        $numberOfEmployeesInTeam = $team->employees()->count();
        $numberOfEmployeesWhoHaveLoggedWorklogs = count($team->worklogsForDate($date));
        $percent = $numberOfEmployeesWhoHaveLoggedWorklogs * 100 / $numberOfEmployeesInTeam;

        $indicator = 'red';

        if ($percent >= 20 && $percent <= 80) {
            $indicator = 'yellow';
        }

        if ($percent > 80) {
            $indicator = 'green';
        }

        $data = [
            'day' => $date->isoFormat('dddd'),
            'date' => DateHelper::formatMonthAndDay($date),
            'friendlyDate' => $date->format('Y-m-d'),
            'status' => DateHelper::determineDateStatus($date),
            'completionRate' => $indicator,
            'numberOfEmployeesInTeam' => $numberOfEmployeesInTeam,
            'numberOfEmployeesWhoHaveLoggedWorklogs' => $numberOfEmployeesWhoHaveLoggedWorklogs,
        ];

        return $data;
    }


    /**
     * Prepares an array containing all the information regarding the worklogs
     * logged on a specific day with the morale.
     *
     * This will be used on the Employee page.
     */
    public static function getDailyInformationForEmployee(Carbon $date, Worklog $worklog = null, Morale $morale = null): array
    {
        $data = [
            'date' => DateHelper::formatShortDateWithTime($date),
            'friendly_date' => DateHelper::formatDayAndMonthInParenthesis($date),
            'status' => DateHelper::determineDateStatus($date),
            'worklog_parsed_content' => is_null($worklog) ? null : StringHelper::parse($worklog->content),
            'morale' => is_null($morale) ? null : $morale->emoji,
        ];

        return $data;
    }

    /**
     * Get a collection representing all the years the employee has logged a
     * worklog for.
     */
    public static function getYears(Collection $worklogs): Collection
    {
        $yearsCollection = collect([]);
        foreach ($worklogs as $worklog) {
            $year = Carbon::createFromFormat('Y-m-d H:i:s', $worklog->created_at)->format('Y');
            $yearsCollection->push([
                'number' => intval($year),
            ]);
        }
        $yearsCollection = $yearsCollection->unique()->sortBy(function ($product) {
            return $product['number'];
        });

        return $yearsCollection;
    }

    /**
     * Get a collection representing all the months the employee has logged a
     * worklog for, for a given year.
     */
    public static function getMonths(Collection $worklogs, int $year): Collection
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

    /**
     * Prepare a yearly calendar containing all the days in a year along with the
     * information whether the employee has a worklog for that day or not.
     */
    public static function getYearlyCalendar(Collection $worklogs, int $year): Collection
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
}
