<?php

namespace App\Http\ViewHelpers\Dashboard;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Timesheet;
use Illuminate\Support\Collection;
use App\Services\Company\Employee\Timesheet\CreateOrGetTimesheet;

class DashboardTimesheetViewHelper
{
    /**
     * Get information about a given timesheet.
     * A timesheet is a set of rows.
     * Each row is a task from a project.
     * Each row is split into 7 days, each one being the entry for the task
     * for the given day.
     *
     * @param Timesheet $timesheet
     * @return array
     */
    public static function show(Timesheet $timesheet): array
    {
        // details about the timesheet
        $entries = $timesheet->timeTrackingEntries()
            ->whereNotNull('project_task_id')
            ->with('projectTask')
            ->with('project')
            ->get();

        // let's iterate over each task in this timesheet to group entries
        $uniqueTasks = $entries->unique('project_task_id');
        $linesOfTimesheet = collect([]);
        foreach ($uniqueTasks as $uniqueTask) {
            $uniqueTask = $uniqueTask->projectTask;
            $entriesForThisTask = $entries->where('project_task_id', $uniqueTask->id);

            $entriesCollection = collect([]);
            $monday = $timesheet->started_at;
            $totalOfMinutesThisWeek = 0;
            for ($day = 0; $day < 7; $day++) {
                $currentDay = $monday->copy()->addDays($day);
                $entriesForTheday = $entriesForThisTask->filter(function ($entry) use ($currentDay) {
                    return $entry->happened_at == $currentDay;
                });

                $totalOfMinutes = 0;
                foreach ($entriesForTheday as $entry) {
                    $totalOfMinutes = $totalOfMinutes + $entry->duration;
                    $totalOfMinutesThisWeek = $totalOfMinutesThisWeek + $totalOfMinutes;
                }

                $statistics = TimeHelper::convertToHoursAndMinutes($totalOfMinutes);
                $entriesCollection->push([
                    'day_of_week' => $currentDay->dayOfWeek,
                    'total_of_minutes' => $totalOfMinutes,
                    'hours' => (int) $statistics['hours'],
                    'minutes' => (int) $statistics['minutes'],
                ]);
            }

            $project = $uniqueTask->project;

            $linesOfTimesheet->push([
                'project_id' => $project->id,
                'project_name' => $project->name,
                'project_code' => $project->code,
                'project_url' => route('projects.show', [
                    'company' => $project->company_id,
                    'project' => $project->id,
                ]),
                'task_id' => $uniqueTask->id,
                'task_title' => $uniqueTask->title,
                'total_this_week' => $totalOfMinutesThisWeek,
                'days' => $entriesCollection,
            ]);
        }

        return [
            'id' => $timesheet->id,
            'status' => $timesheet->status,
            'start_date' => DateHelper::formatDate($timesheet->started_at),
            'end_date' => DateHelper::formatDate($timesheet->ended_at),
            'entries' => $linesOfTimesheet,
            'url' => [
                'project_list' => route('dashboard.timesheet.projects', [
                    'company' => $timesheet->company_id,
                ]),
            ],
        ];
    }

    /**
     * Get the information about the approver, if the timesheet has been either
     * approved or rejected.
     *
     * @param Timesheet $timesheet
     * @param Employee $employee
     * @return array
     */
    public static function approverInformation(Timesheet $timesheet, Employee $employee): array
    {
        if ($timesheet->status != Timesheet::APPROVED && $timesheet->status != Timesheet::REJECTED) {
            return [];
        }

        $information = [];
        if (! $timesheet->approver_id) {
            $information = [
                'name' => $timesheet->approver_name,
                'approved_at' => DateHelper::formatDate($timesheet->approved_at),
            ];
        } else {
            $approver = $timesheet->approver;

            $information = [
                'id' => $approver->id,
                'name' => $approver->name,
                'approved_at' => DateHelper::formatDate($timesheet->approved_at, $employee->timezone),
                'avatar' => ImageHelper::getAvatar($approver),
                'url' => route('employees.show', [
                    'company' => $timesheet->company,
                    'employee' => $approver,
                ]),
            ];
        }

        return $information;
    }

    public static function daysHeader(Timesheet $timesheet): array
    {
        // array of days of the week, to populate the timesheet header
        return [
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

    public static function currentTimesheet(Employee $employee): array
    {
        $currentTimesheet = (new CreateOrGetTimesheet)->execute([
            'company_id' => $employee->company_id,
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        return [
            'id' => $currentTimesheet->id,
            'url' => route('dashboard.timesheet.show', [
                'company' => $employee->company,
                'timesheet' => $currentTimesheet,
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
    public static function availableTasks(Project $project, Timesheet $timesheet): Collection
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

    /**
     * Get a collection of all the timesheets rejected for this employee.
     *
     * @param Employee $employee
     * @return Collection
     */
    public static function rejectedTimesheets(Employee $employee): Collection
    {
        $timesheets = $employee->timesheets()
            ->where('status', Timesheet::REJECTED)
            ->get();

        $timesheetsCollection = collect([]);
        foreach ($timesheets as $timesheet) {
            $timesheetsCollection->push([
                'id' => $timesheet->id,
                'started_at' => DateHelper::formatDate($timesheet->started_at),
                'url' => route('dashboard.timesheet.show', [
                    'company' => $employee->company,
                    'timesheet' => $timesheet,
                ]),
            ]);
        }

        return $timesheetsCollection;
    }
}
