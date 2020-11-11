<?php

namespace App\Http\Controllers\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use App\Services\Company\Project\CreateProjectTask;
use App\Services\Company\Project\ToggleProjectTask;
use App\Services\Company\Project\UpdateProjectTask;
use App\Services\Company\Project\DestroyProjectTask;
use App\Http\ViewHelpers\Project\ProjectTasksViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\AssignProjecTaskToEmployee;

class ProjectTasksController extends Controller
{
    /**
     * Display the list of messages in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Project/Tasks/Index', [
            'tab' => 'tasks',
            'project' => ProjectViewHelper::info($project),
            'tasks' => ProjectTasksViewHelper::index($project),
            'members' => ProjectTasksViewHelper::members($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
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
            $task = (new AssignProjecTaskToEmployee)->execute([
                'company_id' => $company->id,
                'author_id' => $loggedEmployee->id,
                'project_id' => $projectId,
                'project_task_id' => $task->id,
                'assignee_id' => $request->input('assignee_id'),
            ]);
        }

        return response()->json([
            'data' => ProjectTasksViewHelper::show($task, $company),
        ], 201);
    }

    /**
     * Actually update the task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
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
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $task = (new UpdateProjectTask)->execute($data);

        if ($request->input('assignee_id')) {
            $task = (new AssignProjecTaskToEmployee)->execute([
                'company_id' => $company->id,
                'author_id' => $loggedEmployee->id,
                'project_id' => $projectId,
                'project_task_id' => $task->id,
                'assignee_id' => $request->input('assignee_id'),
            ]);
        }

        return response()->json([
            'data' => ProjectTasksViewHelper::show($task, $company),
        ], 201);
    }

    /**
     * Actually update the message.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectTaskId
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
            'data' => ProjectTasksViewHelper::show($task, $company),
        ], 201);
    }

    /**
     * Destroy the task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $taskId
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
        ], 201);
    }
}
