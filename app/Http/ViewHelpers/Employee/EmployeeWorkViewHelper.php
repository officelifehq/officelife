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
     * @param Employee $employee
     * @param Employee $loggedEmployee
     * @param Carbon $startOfWeek
     * @return array
     */
    public static function worklogs(Employee $employee, Employee $loggedEmployee, Carbon $startOfWeek): array
    {
        $worklogs = $employee->worklogs()->whereBetween('created_at', [$startOfWeek, $startOfWeek->copy()->endOfWeek()])->get();
        $morales = $employee->morales()->whereBetween('created_at', [$startOfWeek, $startOfWeek->copy()->endOfWeek()])->get();

        $worklogsCollection = collect([]);
        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->startOfWeek()->addDays($i);

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
                'worklog_parsed_content' => $worklog ? StringHelper::parse($worklog->content) : null,
                'morale' => $morale && $loggedEmployee->id == $employee->id ? $morale->emoji : null,
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
