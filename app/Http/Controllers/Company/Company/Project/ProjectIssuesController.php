<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Response;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProjectIssue;

class ProjectIssuesController extends Controller
{
    /**
     * Display the New issue view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $boardId
     * @param int $sprintId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function store(Request $request, int $companyId, int $projectId, int $boardId, int $sprintId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_board_id' => $boardId,
            'project_sprint_id' => $sprintId,
            'issue_type_id' => $request->input('type'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        $issue = (new CreateProjectIssue)->execute($data);

        return response()->json([
            'data' => [
                'id' => $issue->id,
                'key' => $issue->key,
                'title' => $issue->title,
                'slug' => $issue->slug,
                'created_at' => DateHelper::formatMonthAndDay($issue->created_at),
                'story_points' => $issue->story_points,
                'type' => $issue->type ? [
                    'name' => $issue->type->name,
                    'icon_hex_color' => $issue->type->icon_hex_color,
                ] : null,
                'url' => route('projects.issues.show', [
                    'company' => $loggedCompany,
                    'project' => $projectId,
                    'board' => $boardId,
                    'sprint' => $sprintId,
                    'issue' => $issue,
                ]),
            ],
        ], 201);
    }
}
