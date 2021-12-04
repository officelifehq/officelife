<?php

namespace App\Http\Controllers\Company\Company\Project\ProjectIssue;

use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Http\Controllers\Controller;
use App\Models\Company\ProjectIssue;
use App\Services\Company\Project\AssignAssigneeToProjectIssue;
use App\Services\Company\Project\RemoveAssigneeFromProjectIssue;
use App\Http\ViewHelpers\Company\Project\ProjectIssuesViewHelper;

class ProjectIssueAssigneesController extends Controller
{
    /**
     * Get the list of members in the project.
     *
     * @param Request $request
     * @param integer $companyId
     * @param integer $projectId
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $project = Project::where('company_id', $companyId)
            ->findOrFail($projectId);

        $members = ProjectIssuesViewHelper::members($project);

        return response()->json([
            'data' => $members,
        ], 200);
    }

    /**
     * Save the assignee.
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

        $issue = ProjectIssue::findOrFail($issueId);

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_issue_id' => $issueId,
            'employee_id' => $request->input('id'),
        ];

        $assignee = (new AssignAssigneeToProjectIssue)->execute($data);

        return response()->json([
            'data' => [
                'id' => $assignee->id,
                'name' => $assignee->name,
                'avatar' => ImageHelper::getAvatar($assignee, 25),
                'url' => [
                    'show' => route('employees.show', [
                        'company' => $assignee->company_id,
                        'employee' => $assignee,
                    ]),
                    'destroy' => route('projects.issues.assignees.destroy', [
                        'company' => $assignee->company_id,
                        'project' => $issue->project->id,
                        'board' => $issue->board->id,
                        'issue' => $issue->id,
                        'assignee' => $assignee->id,
                    ]),
                ],
            ],
        ], 200);
    }

    /**
     * Remove the assignee.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $boardId
     * @param int $issueId
     * @param int $assigneeId
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $boardId, int $issueId, int $assigneeId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_issue_id' => $issueId,
            'employee_id' => $assigneeId,
        ];

        (new RemoveAssigneeFromProjectIssue)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
