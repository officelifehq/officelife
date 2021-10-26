<?php

namespace App\Http\Controllers\Company\Company\Project;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\TimeHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Helpers\NotificationHelper;
use App\Models\Company\ProjectTask;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProjectTask;
use App\Services\Company\Project\ToggleProjectTask;
use App\Services\Company\Project\UpdateProjectTask;
use App\Services\Company\Project\DestroyProjectTask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Services\Company\Project\AssignProjectTaskToEmployee;
use App\Http\ViewHelpers\Company\Project\ProjectTasksViewHelper;
use App\Services\Company\Employee\Timesheet\CreateTimeTrackingEntry;

class ProjectTasksController extends Controller
{
    /**
     * Display the list of tasks in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Tasks/Index', [
            'tab' => 'tasks',
            'project' => ProjectViewHelper::info($project),
            'tasks' => ProjectTasksViewHelper::index($project),
            'members' => ProjectTasksViewHelper::members($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the detail of a task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, int $projectId, int $taskId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        try {
            $projectTask = ProjectTask::where('project_id', $project->id)
                ->with('assignee')
                ->with('list')
                ->findOrFail($taskId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/Project/Tasks/Show', [
            'tab' => 'tasks',
            'project' => ProjectViewHelper::info($projectTask->project),
            'task' => ProjectTasksViewHelper::taskDetails($projectTask, $company, $employee),
            'members' => ProjectTasksViewHelper::members($project),
            'lists' => ProjectTasksViewHelper::taskLists($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'project_task_list_id' => $request->input('task_list_id'),
        ];

        $task = (new CreateProjectTask)->execute($data);

        if ($request->input('assignee_id')) {
            $task = (new AssignProjectTaskToEmployee)->execute([
                'company_id' => $company->id,
                'author_id' => $loggedEmployee->id,
                'project_id' => $projectId,
                'project_task_id' => $task->id,
                'assignee_id' => $request->input('assignee_id'),
            ]);
        }

        return response()->json([
            'data' => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'completed' => $task->completed,
                'url' => route('projects.tasks.show', [
                    'company' => $company,
                    'project' => $task->project_id,
                    'task' => $task->id,
                ]),
                'duration' => null,
                'assignee' => $task->assignee ? [
                    'id' => $task->assignee->id,
                    'name' => $task->assignee->name,
                    'avatar' => ImageHelper::getAvatar($task->assignee),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $task->assignee,
                    ]),
                ] : null,
            ],
        ], 201);
    }

    /**
     * Actually update the task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $projectId, int $taskId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_id' => $taskId,
            'project_task_list_id' => $request->input('task_list_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        $task = (new UpdateProjectTask)->execute($data);

        if ($request->input('assignee_id')) {
            $task = (new AssignProjectTaskToEmployee)->execute([
                'company_id' => $company->id,
                'author_id' => $loggedEmployee->id,
                'project_id' => $projectId,
                'project_task_id' => $task->id,
                'assignee_id' => $request->input('assignee_id'),
            ]);
        }

        $duration = DB::table('time_tracking_entries')
            ->where('project_task_id', $task->id)
            ->sum('duration');

        return response()->json([
            'data' => [
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
                'assignee' => $task->assignee ? [
                    'id' => $task->assignee->id,
                    'name' => $task->assignee->name,
                    'avatar' => ImageHelper::getAvatar($task->assignee),
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $task->assignee,
                    ]),
                ] : null,
            ],
        ], 201);
    }

    /**
     * Actually update the message.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectTaskId
     *
     * @return JsonResponse
     */
    public function toggle(Request $request, int $companyId, int $projectId, int $projectTaskId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_id' => $projectTaskId,
        ];

        $task = (new ToggleProjectTask)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Destroy the task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $taskId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_id' => $taskId,
        ];

        (new DestroyProjectTask)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Get the time tracking entries for the given task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
     *
     * @return JsonResponse
     */
    public function timeTrackingEntries(Request $request, int $companyId, int $projectId, int $taskId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        try {
            $projectTask = ProjectTask::where('project_id', $project->id)
                ->findOrFail($taskId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return response()->json([
            'data' => ProjectTasksViewHelper::timeTrackingEntries($projectTask, $company, $employee),
        ], 200);
    }

    /**
     * Get the time tracking entries for the given task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
     *
     * @return JsonResponse
     */
    public function logTime(Request $request, int $companyId, int $projectId, int $taskId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        $entry = (new CreateTimeTrackingEntry)->execute([
            'author_id' => $employee->id,
            'employee_id' => $employee->id,
            'company_id' => $company->id,
            'project_id' => $projectId,
            'project_task_id' => $taskId,
            'duration' => $request->input('duration'),
            'date' => Carbon::now()->format('Y-m-d'),
        ]);

        $duration = DB::table('time_tracking_entries')
            ->where('project_task_id', $taskId)
            ->sum('duration');

        $totalDuration = TimeHelper::durationInHumanFormat($duration);

        return response()->json([
            'data' => $totalDuration,
        ], 201);
    }
}
