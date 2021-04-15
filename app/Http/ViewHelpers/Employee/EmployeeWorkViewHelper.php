<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;
use App\Helpers\StringHelper;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class EmployeeWorkViewHelper
{
    /**
     * Get all worklogs with the morale for a given time period.
     * The page is structured as follow:
     * - list of 4 previous weeks
     * - for each week, the worklogs of the 5 working days + the morale of the
     * employee (if the logged employee has the right to view this information).
     *
     * @param Employee $employee
     * @param Employee $loggedEmployee
     * @param Carbon $startOfWeek
     * @param Carbon $selectedDay
     * @return array
     */
    public static function worklog(Employee $employee, Employee $loggedEmployee, Carbon $startOfWeek, Carbon $selectedDay): array
    {
        $worklogs = $employee->worklogs()->whereBetween('created_at', [$startOfWeek, $startOfWeek->copy()->endOfWeek()])->get();

        $daysCollection = collect();
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $worklog = $worklogs->first(function ($worklog) use ($day) {
                return $worklog->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $day = $startOfWeek->copy()->startOfWeek()->addDays($i);

            $daysCollection->push([
                'id' => Str::uuid()->toString(), // it doesn't matter here, this is just for Vue and having an unique key
                'day' => DateHelper::day($day),
                'day_number' => DateHelper::dayWithShortMonth($day),
                'date' => $day->format('Y-m-d'),
                'status' => DateHelper::determineDateStatus($day),
                'selected' => $selectedDay->format('Y-m-d') == $day->format('Y-m-d') ? 'selected' : '',
                'worklog_done_for_this_day' => $worklog ? 'green' : '',
            ]);
        }

        $worklog = $employee->worklogs()->whereDate('created_at', $selectedDay)->first();
        $morale = $employee->morales()->whereDate('created_at', $selectedDay)->first();

        $array = [
            'days' => $daysCollection,
            'current_week' => $startOfWeek->copy()->format('Y-m-d'),
            'worklog_parsed_content' => $worklog ? StringHelper::parse($worklog->content) : null,
            'morale' => $morale && $loggedEmployee->id == $employee->id ? $morale->emoji : null,
        ];

        return $array;
    }

    /**
     * Get the current week date, and the three weeks prior to that.
     *
     * @param Employee $loggedEmployee
     * @return Collection
     */
    public static function weeks(Employee $loggedEmployee): Collection
    {
        $date = Carbon::now()->setTimezone($loggedEmployee->timezone);
        $currentWeek = $date->copy()->startofWeek();

        $weeksCollection = collect([]);
        $weeksCollection->push([
            'id' => 1,
            'label' => '3 weeks ago',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(3)->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(3)->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->copy()->subWeeks(3)->format('Y-m-d'),
        ]);
        $weeksCollection->push([
            'id' => 2,
            'label' => '2 weeks ago',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(2)->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeeks(2)->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->copy()->subWeeks(2)->format('Y-m-d'),
        ]);
        $weeksCollection->push([
            'id' => 3,
            'label' => 'Last week',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeek()->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->subWeek()->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->copy()->subWeek()->format('Y-m-d'),
        ]);
        $weeksCollection->push([
            'id' => 4,
            'label' => 'Current week',
            'range' => [
                'start' => DateHelper::formatMonthAndDay($currentWeek->copy()->startOfWeek()),
                'end' => DateHelper::formatMonthAndDay($currentWeek->copy()->endOfWeek()),
            ],
            'start_of_week_date' => $currentWeek->format('Y-m-d'),
        ]);

        return $weeksCollection;
    }
}
