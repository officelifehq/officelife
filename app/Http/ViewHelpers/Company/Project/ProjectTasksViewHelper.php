<?php

namespace App\Http\ViewHelpers\Company\Project;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\TimeHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectTaskList;

class ProjectTasksViewHelper
{
    /**
     * Collection containing the information about the tasks in the project.
     * This collection contains all the tasks as well as all the tasks lists.
     *
     * @param Project $project
     * @return array
     */
    public static function index(Project $project): array
    {
        $company = $project->company;

        $tasks = $project->tasks()
            ->with('list')
            ->with('assignee')
            ->with('author')
            ->orderBy('id', 'asc')
            ->get();

        // the goal of the following is to first display tasks without lists,
        // and after this, tasks with lists, grouped by lists.
        // the trick is to do this with a single query, as we don’t want to do
        // multiple queries to slow down the loading speed of the page.

        $tasksWithoutLists = $tasks->filter(function ($task) {
            return is_null($task->project_task_list_id);
        });
        $tasksWithoutListsCollection = collect([]);
        foreach ($tasksWithoutLists as $task) {
            $duration = DB::table('time_tracking_entries')
                ->where('project_task_id', $task->id)
                ->sum('duration');

            $assignee = $task->assignee;

            $tasksWithoutListsCollection->push([
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'completed' => $task->completed,
                'duration' => $duration != 0 ? TimeHelper::durationInHumanFormat($duration) : null,
                'url' => route('projects.tasks.show', [
                    'company' => $company,
                    'project' => $task->project_id,
                    'task' => $task->id,
                ]),
                'assignee' => $assignee ? [
                    'id' => $assignee->id,
                    'name' => $assignee->name,
                    'avatar' => $assignee->avatar,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $assignee,
                    ]),
                ] : null,
            ]);
        }

        $tasksWithLists = $tasks->diff($tasksWithoutLists);

        // get the list of unique task list ids
        $taskLists = $project->lists()
            ->orderBy('id', 'asc')
            ->get();

        $tasksListCollection = collect([]);
        foreach ($taskLists as $taskList) {
            $tasksWithListsCollection = collect([]);

            // all the tasks in this task list
            $tasks = $tasksWithLists->filter(function ($task) use ($taskList) {
                return $task->project_task_list_id == $taskList->id;
            });

            foreach ($tasks as $task) {
                $duration = DB::table('time_tracking_entries')
                    ->where('project_task_id', $task->id)
                    ->sum('duration');

                $assignee = $task->assignee;

                $tasksWithListsCollection->push([
                    'id' => $task->id,
                    'title' => $task->title,
                    'description' => $task->description,
                    'completed' => $task->completed,
                    'duration' => $duration != 0 ? TimeHelper::durationInHumanFormat($duration) : null,
                    'url' => route('projects.tasks.show', [
                        'company' => $company,
                        'project' => $task->project_id,
                        'task' => $task->id,
                    ]),
                    'assignee' => $assignee ? [
                        'id' => $assignee->id,
                        'name' => $assignee->name,
                        'avatar' => $assignee->avatar,
                        'url' => route('employees.show', [
                            'company' => $company,
                            'employee' => $assignee,
                        ]),
                    ] : null,
                ]);
            }

            $tasksListCollection->push([
                'id' => $taskList->id,
                'title' => $taskList->title,
                'description' => $taskList->description,
                'tasks' => $tasksWithListsCollection,
            ]);
        }

        return [
            'tasks_without_lists' => $tasksWithoutListsCollection,
            'task_lists' => $tasksListCollection,
        ];
    }

    /**
     * Array containing all the information about the given task list info.
     *
     * @param ProjectTaskList $taskList
     * @return array
     */
    public static function getTaskListInfo(ProjectTaskList $taskList): array
    {
        return [
            'id' => $taskList->id,
            'title' => $taskList->title,
            'description' => $taskList->description,
        ];
    }

    /**
     * Collection containing all the potential project members.
     *
     * @param Project $project
     * @return Collection|null
     */
    public static function members(Project $project): ?Collection
    {
        return $project->employees()->notLocked()
            ->get()
            ->map(function ($employee) {
                return [
                    'value' => $employee->id,
                    'label' => $employee->name,
                ];
            });
    }

    /**
     * Get the complete details about the task.
     *
     * @param ProjectTask $task
     * @param Company $company
     * @return array
     */
    public static function getTaskFullDetails(ProjectTask $task, Company $company): array
    {
        $author = $task->author;
        $assignee = $task->assignee;
        $duration = $task->timeTrackingEntries()->sum('duration');
        $role = null;

        if ($author) {
            $role = DB::table('employee_project')
                ->where('employee_id', $author->id)
                ->where('project_id', $task->project_id)
                ->select('role', 'created_at')
                ->first();
        }

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'completed' => $task->completed,
            'completed_at' => $task->completed_at ? DateHelper::formatDate($task->completed_at) : null,
            'created_at' => DateHelper::formatDate($task->created_at),
            'url' => route('projects.tasks.show', [
                'company' => $company,
                'project' => $task->project_id,
                'task' => $task->id,
            ]),
            'duration' => $duration != 0 ? TimeHelper::durationInHumanFormat($duration) : null,
            'author' => $author ? [
                'id' => $author->id,
                'name' => $author->name,
                'avatar' => $author->avatar,
                'role' => $role ? $role->role : null,
                'added_at' => $role ? DateHelper::formatDate(Carbon::createFromFormat('Y-m-d H:i:s', $role->created_at)) : null,
                'position' => (! $author->position) ? null : $author->position->title,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $author,
                ]),
            ] : null,
            'assignee' => $assignee ? [
                'id' => $assignee->id,
                'name' => $assignee->name,
                'avatar' => $assignee->avatar,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $assignee,
                ]),
            ] : null,
        ];
    }

    /**
     * Array containing all the information about a specific project task.
     *
     * @param ProjectTask $projectTask
     * @param Company $company
     * @return array|null
     */
    public static function taskDetails(ProjectTask $projectTask, Company $company): ?array
    {
        $duration = DB::table('time_tracking_entries')
            ->where('project_task_id', $projectTask->id)
            ->sum('duration');

        $task = self::getTaskFullDetails($projectTask, $company);

        return [
            'task' => $task,
            'total_duration' => TimeHelper::durationInHumanFormat($duration),
            'list' => [
                'name' => $projectTask->list ? $projectTask->list->title : null,
            ],
        ];
    }

    /**
     * Collection of time tracking entries for a given task.
     *
     * @param ProjectTask $projectTask
     * @param Company $company
     * @return Collection|null
     */
    public static function timeTrackingEntries(ProjectTask $projectTask, Company $company): ?Collection
    {
        // we need to query this using a raw query instead of hydrating all
        // models with eloquent, as we might have a lot of time tracking entries
        // on long tasks, and this would not be efficient at all
        $timeTrackingEntries = DB::table('time_tracking_entries')
            ->join('employees', 'time_tracking_entries.employee_id', '=', 'employees.id')
            ->select('time_tracking_entries.id', 'time_tracking_entries.duration', 'time_tracking_entries.happened_at', 'employees.id as employee_id', 'employees.avatar', 'employees.first_name', 'employees.last_name')
            ->where('project_task_id', $projectTask->id)
            ->orderBy('time_tracking_entries.happened_at', 'desc')
            ->get();

        $timeTrackingCollection = collect([]);
        foreach ($timeTrackingEntries as $timeTrackingEntry) {
            $carbonDate = Carbon::createFromFormat('Y-m-d H:i:s', $timeTrackingEntry->happened_at);

            $timeTrackingCollection->push([
                'id' => (int) $timeTrackingEntry->id,
                'duration' => TimeHelper::durationInHumanFormat((int) $timeTrackingEntry->duration),
                'created_at' => DateHelper::formatDate($carbonDate),
                'employee' => [
                    'id' => $timeTrackingEntry->employee_id,
                    'name' => $timeTrackingEntry->first_name.' '.$timeTrackingEntry->last_name,
                    'avatar' => $timeTrackingEntry->avatar,
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $timeTrackingEntry->employee_id,
                    ]),
                ],
            ]);
        }

        return $timeTrackingCollection;
    }
}
