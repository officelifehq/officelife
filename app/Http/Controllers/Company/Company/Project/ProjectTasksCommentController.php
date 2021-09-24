<?php

namespace App\Http\Controllers\Company\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProjectTaskComment;
use App\Services\Company\Project\UpdateProjectTaskComment;
use App\Services\Company\Project\DestroyProjectTaskComment;

class ProjectTasksCommentController extends Controller
{
    /**
     * Create the task's comment.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectTaskId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $projectId, int $projectTaskId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_id' => $projectTaskId,
            'content' => $request->input('comment'),
        ];

        $comment = (new CreateProjectTaskComment)->execute($data);

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
     * @param int $projectTaskId
     * @param int $commentId
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $projectId, int $projectTaskId, int $commentId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_id' => $projectTaskId,
            'comment_id' => $commentId,
            'content' => $request->input('commentEdit'),
        ];

        $comment = (new UpdateProjectTaskComment)->execute($data);

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
        ], 200);
    }

    /**
     * Destroy the comment.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $projectTaskId
     * @param int $commentId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $projectTaskId, int $commentId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_id' => $projectTaskId,
            'comment_id' => $commentId,
        ];

        (new DestroyProjectTaskComment)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
