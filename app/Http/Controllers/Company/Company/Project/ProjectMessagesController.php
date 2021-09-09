<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
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
use App\Services\Company\Project\CreateProjectMessageComment;
use App\Services\Company\Project\UpdateProjectMessageComment;
use App\Services\Company\Project\DestroyProjectMessageComment;
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

        try {
            $project = Project::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

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

        try {
            $project = Project::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

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

    /**
     * Create the message's comment.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectMessageId
     *
     * @return JsonResponse
     */
    public function storeComment(Request $request, int $companyId, int $projectId, int $projectMessageId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_message_id' => $projectMessageId,
            'content' => $request->input('comment'),
        ];

        $comment = (new CreateProjectMessageComment)->execute($data);

        return response()->json([
            'data' => [
                'id' => $comment->id,
                'content' => StringHelper::parse($comment->content),
                'content_raw' => $comment->content,
                'written_at' => DateHelper::formatShortDateWithTime($comment->created_at),
                'author' => [
                    'id' => $loggedEmployee->id,
                    'name' => $loggedEmployee->name,
                    'avatar' => ImageHelper::getAvatar($loggedEmployee, 32),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $loggedEmployee,
                    ]),
                ],
                'can_edit' => true,
                'can_delete' => true,
            ],
        ], 201);
    }

    /**
     * Edit a comment.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectMessageId
     * @param int $commentId
     *
     * @return JsonResponse
     */
    public function updateComment(Request $request, int $companyId, int $projectId, int $projectMessageId, int $commentId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_message_id' => $projectMessageId,
            'comment_id' => $commentId,
            'content' => $request->input('commentEdit'),
        ];

        $comment = (new UpdateProjectMessageComment)->execute($data);

        return response()->json([
            'data' => [
                'id' => $comment->id,
                'content' => StringHelper::parse($comment->content),
                'content_raw' => $comment->content,
                'written_at' => DateHelper::formatShortDateWithTime($comment->created_at),
                'author' => [
                    'id' => $loggedEmployee->id,
                    'name' => $loggedEmployee->name,
                    'avatar' => ImageHelper::getAvatar($loggedEmployee, 32),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $loggedEmployee,
                    ]),
                ],
                'can_edit' => true,
                'can_delete' => true,
            ],
        ], 201);
    }

    /**
     * Destroy the message.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectMessageId
     * @param int $commentId
     *
     * @return JsonResponse
     */
    public function destroyComment(Request $request, int $companyId, int $projectId, int $projectMessageId, int $commentId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_message_id' => $projectMessageId,
            'comment_id' => $commentId,
        ];

        (new DestroyProjectMessageComment)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
