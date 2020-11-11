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
use App\Models\Company\ProjectMessage;
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use App\Services\Company\Project\CreateProjectTask;
use App\Services\Company\Project\UpdateProjectMessage;
use App\Services\Company\Project\DestroyProjectMessage;
use App\Http\ViewHelpers\Project\ProjectTasksViewHelper;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\MarkProjectMessageasRead;
use App\Http\ViewHelpers\Project\ProjectMessagesViewHelper;

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

        return response()->json([
            'data' => ProjectTasksViewHelper::show($task, $company),
        ], 201);
    }

    /**
     * Display the detail of a given message.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $messageId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function show(Request $request, int $companyId, int $projectId, int $messageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $loggedCompany->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $message = ProjectMessage::where('project_id', $project->id)
                ->with('project')
                ->findOrFail($messageId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        (new MarkProjectMessageasRead)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $project->id,
            'project_message_id' => $message->id,
        ]);

        return Inertia::render('Project/Messages/Show', [
            'tab' => 'messages',
            'project' => ProjectViewHelper::info($project),
            'message' => ProjectMessagesViewHelper::show($message),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the edit message page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $messageId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function edit(Request $request, int $companyId, int $projectId, int $messageId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $message = ProjectMessage::where('project_id', $project->id)
                ->with('project')
                ->findOrFail($messageId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Project/Messages/Update', [
            'tab' => 'messages',
            'project' => ProjectViewHelper::info($project),
            'message' => ProjectMessagesViewHelper::edit($message),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Actually update the message.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectMessageId
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $projectId, int $projectMessageId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_message_id' => $projectMessageId,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
        ];

        $message = (new UpdateProjectMessage)->execute($data);

        return response()->json([
            'data' => $message->id,
        ], 201);
    }

    /**
     * Destroy the message.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectMessageId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $projectMessageId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_message_id' => $projectMessageId,
        ];

        (new DestroyProjectMessage)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }
}
