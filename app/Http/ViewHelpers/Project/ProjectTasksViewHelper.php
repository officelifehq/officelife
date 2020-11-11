<?php

namespace App\Http\ViewHelpers\Project;

use App\Helpers\DateHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use Illuminate\Support\Collection;
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
            ->latest()
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
            $tasksWithoutListsCollection->push(self::getTaskInfo($task, $company));
        }

        // get the list of unique task list ids
        $tasksWithLists = $tasks->diff($tasksWithoutLists);
        $uniqueTaskListIds = $tasksWithLists->map->only('project_task_list_id')->unique();

        $tasksListCollection = collect([]);
        foreach ($uniqueTaskListIds as $uniqueListId) {
            $tasksWithListsCollection = collect([]);
            $uniqueId = $uniqueListId['project_task_list_id'];

            $taskList = ProjectTaskList::find($uniqueId);

            $tasks = $tasksWithLists->filter(function ($task) use ($uniqueId) {
                return $task->project_task_list_id == $uniqueId;
            });

            foreach ($tasks as $task) {
                $tasksWithListsCollection->push(self::getTaskInfo($task, $company));
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
     * Contains all the information about a single task.
     *
     * @param ProjectTask $task
     * @param Company $company
     * @return array
     */
    public static function show(ProjectTask $task, Company $company): array
    {
        return self::getTaskInfo($task, $company);
    }

    private static function getTaskInfo(ProjectTask $task, Company $company): array
    {
        $author = $task->author;
        $assignee = $task->assignee;

        return [
            'id' => $task->id,
            'title' => $task->title,
            'description' => $task->description,
            'completed' => $task->completed,
            'completed_at' => $task->completed_at ? DateHelper::formatDate($task->completed_at) : null,
            'author' => $author ? [
                'id' => $author->id,
                'name' => $author->name,
                'avatar' => $author->avatar,
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
     * Collection containing all the potential project members.
     *
     * @param Project $projet
     * @return Collection|null
     */
    public static function members(Project $project): ?Collection
    {
        $employees = $project->employees()->notLocked()->get();

        $employeesCollection = collect([]);
        foreach ($employees as $employee) {
            $employeesCollection->push([
                'value' => $employee->id,
                'label' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }
}
