<?php

namespace App\Http\ViewHelpers\Dashboard;

use App\Helpers\DateHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;

class DashboardTimesheetViewHelper
{
    /**
     * Array containing information about a given timesheet.
     * A timesheet is a set of rows.
     * Each row is a task from a project.
     * Each row is split into 7 days, each one being the entry for the task
     * for the given day.
     *
     * @param Timesheet $timesheet
     * @param Employee $employee
     * @return array
     */
    public static function show(Timesheet $timesheet, Employee $employee): array
    {
        // details about the timesheet
        $entries = $timesheet->timeTrackingEntries()
            ->whereNotNull('project_task_id')
            ->with('projectTask')
            ->with('project')
            ->get();
        $linesOfTimesheet = collect([]);

        // let's iterate over each task in this timesheet to group entries
        $uniqueTasks = $entries->unique('project_task_id');
        foreach ($uniqueTasks as $uniqueTask) {
            $uniqueTask = $uniqueTask->projectTask;
            $entries = $entries->where('project_task_id', $uniqueTask->id);

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
                'project_id' => $uniqueTask->project->id,
                'project_name' => $uniqueTask->project->name,
                'project_code' => $uniqueTask->project->code,
                'task_id' => $uniqueTask->id,
                'task_title' => $uniqueTask->title,
                'total_this_week' => $totalOfHoursThisWeek,
                'days' => $entriesCollection,
            ]);
        }

        // array of days of the week, to populate the timesheet header
        $days = [
            'monday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()),
                'short' => $timesheet->started_at->copy()->format('D'),
            ],
            'tuesday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()->addDays(1)),
                'short' => $timesheet->started_at->copy()->addDays(1)->format('D'),
            ],
            'wednesday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()->addDays(2)),
                'short' => $timesheet->started_at->copy()->addDays(2)->format('D'),
            ],
            'thursday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()->addDays(3)),
                'short' => $timesheet->started_at->copy()->addDays(3)->format('D'),
            ],
            'friday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()->addDays(4)),
                'short' => $timesheet->started_at->copy()->addDays(4)->format('D'),
            ],
            'saturday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()->addDays(5)),
                'short' => $timesheet->started_at->copy()->addDays(5)->format('D'),
            ],
            'sunday' => [
                'full' => DateHelper::formatShortMonthAndDay($timesheet->started_at->copy()->addDays(6)),
                'short' => $timesheet->started_at->copy()->addDays(6)->format('D'),
            ],
        ];

        return [
            'id' => $timesheet->id,
            'start_date' => DateHelper::formatDate($timesheet->started_at),
            'end_date' => DateHelper::formatDate($timesheet->ended_at),
            'days' => $days,
            'entries' => $linesOfTimesheet,
        ];
    }

    public static function previousTimesheet(Timesheet $timesheet, Employee $employee): array
    {
        $firstDayOfTimesheet = $timesheet->started_at;
        $lastSunday = $firstDayOfTimesheet->subDay();
        $previousTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $timesheet->company_id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => $lastSunday->format('Y-m-d'),
        ]);

        return [
            'id' => $previousTimesheet->id,
            'url' => route('dashboard.timesheet.show', [
                'company' => $employee->company,
                'timesheet' => $previousTimesheet,
            ]),
        ];
    }

    public static function nextTimesheet(Timesheet $timesheet, Employee $employee): array
    {
        $lastDayOfTimesheet = $timesheet->ended_at;
        $nextMonday = $lastDayOfTimesheet->addDay();
        $nextTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $timesheet->company_id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => $nextMonday->format('Y-m-d'),
        ]);

        return [
            'id' => $nextTimesheet->id,
            'url' => route('dashboard.timesheet.show', [
                'company' => $employee->company,
                'timesheet' => $nextTimesheet,
            ]),
        ];
    }

    /**
     * Array containing information about projects the employee is part of.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function projects(Employee $employee): Collection
    {
        $projects = $employee->projects()->get();

        $projectCollection = collect([]);
        foreach ($projects as $project) {
            $projectCollection->push([
                'value' => $project->id,
                'label' => $project->name,
                'code' => $project->code,
            ]);
        }

        return $projectCollection;
    }

    /**
     * Array containing all the tasks in a given project, that are not in
     * the given timesheet already.
     * This method is used when we add a new timesheet row.
     *
     * @param Project $project
     * @param Timesheet $timesheet
     * @return Collection
     */
    public static function tasks(Project $project, Timesheet $timesheet): Collection
    {
        $projectTasks = $project->tasks;

        $timesheetEntries = $timesheet->timeTrackingEntries()
            ->with('projectTask')->get();

        // filter out the tasks already present in the timesheet
        foreach ($timesheetEntries as $entry) {
            if ($entry->projectTask) {
                $projectTasks = $projectTasks->filter(function ($task) use ($entry) {
                    return $task->id != $entry->projectTask->id;
                });
            }
        }

        $taskCollection = collect([]);
        foreach ($projectTasks as $task) {
            $taskCollection->push([
                'value' => $task->id,
                'label' => $task->title,
            ]);
        }

        return $taskCollection;
    }
}
