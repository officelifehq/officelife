<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\ProjectMessage;
use App\Services\Company\Project\CreateProjectMessage;
use App\Services\Company\Project\UpdateProjectMessage;
use App\Services\Company\Project\DestroyProjectMessage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\MarkProjectMessageasRead;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Http\ViewHelpers\Company\Project\ProjectMessagesViewHelper;

class ProjectMessagesController extends Controller
{
    /**
     * Display the list of messages in the project.
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
        $employee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Messages/Index', [
            'tab' => 'messages',
            'project' => ProjectViewHelper::info($project),
            'messages' => ProjectMessagesViewHelper::index($project, $employee),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the Create message view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function create(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Messages/Create', [
            'project' => ProjectViewHelper::info($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the message.
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
            'content' => $request->input('content'),
        ];

        $message = (new CreateProjectMessage)->execute($data);

        return response()->json([
            'data' => $message->id,
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
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, int $projectId, int $messageId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

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

        return Inertia::render('Company/Project/Messages/Show', [
            'tab' => 'messages',
            'project' => ProjectViewHelper::info($project),
            'message' => ProjectMessagesViewHelper::show($message, $loggedEmployee),
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
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function edit(Request $request, int $companyId, int $projectId, int $messageId)
    {
        $company = InstanceHelper::getLoggedCompany();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        try {
            $message = ProjectMessage::where('project_id', $project->id)
                ->with('project')
                ->findOrFail($messageId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/Project/Messages/Update', [
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
     *
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
     *
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
        ], 200);
    }
}
