<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;

class DashboardTimesheetViewHelper
{
    /**
     * Array containing information about a given timesheet.
     *
     * @param Timesheet $timesheet
     * @param Employee $employee
     * @return array
     */
    public static function show(Timesheet $timesheet, Employee $employee): array
    {
        // get previous timesheet id
        $firstDayOfTimesheet = $timesheet->started_at;
        $lastSunday = $firstDayOfTimesheet->subDay();
        $previousTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $timesheet->company_id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => $lastSunday->format('Y-m-d'),
        ]);

        // get next timesheet id
        $lastDayOfTimesheet = $timesheet->ended_at;
        $nextMonday = $lastDayOfTimesheet->addDay();
        $nextTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $timesheet->company_id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => $nextMonday->format('Y-m-d'),
        ]);

        // details about the timesheet
        $entries = $timesheet->timeTrackingEntries()->with('project')->get();
        $entriesWithProjects = $entries->whereNotNull('project_id');
        $entriesWithoutProjects = $entries->whereNull('project_id');
        $linesOfTimesheet = collect([]);

        // let's iterate over each project in this timesheet to group entries
        // by day for this project
        $uniqueProjects = $entriesWithProjects->unique('project_id');
        foreach ($uniqueProjects as $uniqueProject) {
            $uniqueProject = $uniqueProject->project;
            $entries = $entriesWithProjects->where('project_id', $uniqueProject->id);

            $entriesCollection = collect([]);
            $monday = $timesheet->started_at;
            $totalOfHoursThisWeek = 0;
            for ($day = 0; $day < 7; $day++) {
                $currentDay = $monday->copy()->addDays($day);
                $entriesForTheday = $entries->filter(function ($entry) use ($currentDay) {
                    return $entry->happened_at == $currentDay;
                });

                $totalOfHours = 0;
                foreach ($entriesForTheday as $entry) {
                    $totalOfHours = $totalOfHours + $entry->duration;
                    $totalOfHoursThisWeek = $totalOfHoursThisWeek + $totalOfHours;
                }

                $entriesCollection->push([
                    'day_of_week' => $currentDay->dayOfWeek,
                    'total_of_hours' => $totalOfHours,
                ]);
            }

            $linesOfTimesheet->push([
                'id' => $uniqueProject->id,
                'project_name' => $uniqueProject->name,
                'project_code' => $uniqueProject->code,
                'total_this_week' => $totalOfHoursThisWeek,
                'days' => $entriesCollection,
            ]);
        }

        // let's iterate over each entry without a project
        foreach ($entriesWithoutProjects as $entry) {
            $linesOfTimesheet->push([
                'id' => $uniqueProject->id,
                'project_name' => $entry->description,
                'project_code' => null,
                'total_this_week' => $entry->duration,
                'day_of_week' => $entry->happened_at->dayOfWeek,
            ]);
        }

        return [
            'start_date' => DateHelper::formatDate($timesheet->started_at),
            'end_date' => DateHelper::formatDate($timesheet->ended_at),
            'entries' => $linesOfTimesheet,
            'next_timesheet' => $nextTimesheet,
            'previous_timesheet' => $previousTimesheet,
        ];
    }
}
