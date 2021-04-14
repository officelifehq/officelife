<?php

namespace App\Http\ViewHelpers\Employee;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use Illuminate\Support\Str;
use App\Helpers\StringHelper;
use App\Models\Company\Employee;

class EmployeeWorkViewHelper
{
    /**
     * Get all worklogs with the morale for a given time period.
     * The page is structured as follow:
     * - list of 4 previous weeks
     * - for each week, the worklogs of the 5 working days + the morale of the
     * employee (if the logged employee has the right to view this information).
     *
     * Beware, the @firstDayOfWeek has to be a Carbon date with the right timezone
     * properly set.
     *
     * @param Employee $employee
     * @param Employee $loggedEmployee
     * @param Carbon $firstDayOfWeek
     * @return array
     */
    public static function worklogs(Employee $employee, Employee $loggedEmployee, Carbon $firstDayOfWeek): array
    {
        $lastDayOfWeek = $firstDayOfWeek->copy()->endOfWeek();
        $worklogs = $employee->worklogs()->whereBetween('hired_at', [$firstDayOfWeek, $lastDayOfWeek])->get();
        $morales = $employee->morales()->whereBetween('hired_at', [$firstDayOfWeek, $lastDayOfWeek])->get();
        $worklogsCollection = collect([]);

        // worklogs from Monday to Friday of the current week
        for ($i = 0; $i < 5; $i++) {
            $day = $firstDayOfWeek->copy()->startOfWeek()->addDays($i);

            $worklog = $worklogs->first(function ($worklog) use ($day) {
                return $worklog->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $morale = $morales->first(function ($morale) use ($day) {
                return $morale->created_at->format('Y-m-d') == $day->format('Y-m-d');
            });

            $worklogsCollection->push([
                'id' => Str::uuid()->toString(), // it doesn't matter here, this is just for Vue and having an unique key
                'date' => DateHelper::formatDayAndMonthInParenthesis($day),
                'status' => DateHelper::determineDateStatus($day),
                'worklog_parsed_content' => is_null($worklog) ? null : StringHelper::parse($worklog->content),
                'morale' => is_null($morale) ? null : $morale->emoji,
            ]);
        }

        $array = [
            'data' => $worklogsCollection,
            'url' => route('employee.work.worklogs', [
                'company' => $employee->company,
                'employee' => $employee,
            ]),
        ];

        return $array;
    }
}
