<?php

namespace App\Http\Controllers\Company\Company\Project\ProjectIssue;

use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\UpdateProjectIssueStoryPoint;

class ProjectIssuePointsController extends Controller
{
    /**
     * Save the number of story points for the given task.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $boardId
     * @param int $issueId
     */
    public function store(Request $request, int $companyId, int $projectId, int $boardId, int $issueId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_issue_id' => $issueId,
            'points' => $request->input('points'),
        ];

        (new UpdateProjectIssueStoryPoint)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
