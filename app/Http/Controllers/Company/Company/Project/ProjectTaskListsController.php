<?php

namespace App\Http\Controllers\Company\Company\Project;

use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\Project\ProjectTasksViewHelper;
use App\Services\Company\Project\CreateProjectTaskList;
use App\Services\Company\Project\DestroyProjectTaskList;
use App\Services\Company\Project\UpdateProjectTaskList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectTaskListsController extends Controller
{
    /**
     * Create the task list.
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
        ];

        $taskList = (new CreateProjectTaskList)->execute($data);

        return response()->json([
            'data' => ProjectTasksViewHelper::getTaskListInfo($taskList),
        ], 201);
    }

    /**
     * Update the task list.
     */
    public function update(Request $request, int $companyId, int $projectId, int $projectTaskListId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_list_id' => $projectTaskListId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        $taskList = (new UpdateProjectTaskList)->execute($data);

        return response()->json([
            'data' => ProjectTasksViewHelper::getTaskListInfo($taskList),
        ], 200);
    }

    /**
     * Delete the task list.
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $projectTaskListId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_task_list_id' => $projectTaskListId,
        ];

        (new DestroyProjectTaskList)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
