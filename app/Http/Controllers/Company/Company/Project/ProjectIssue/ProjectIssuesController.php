<?php

namespace App\Http\Controllers\Company\Company\Project\ProjectIssue;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Models\Company\ProjectIssue;
use App\Services\Company\Project\CreateProjectIssue;
use App\Services\Company\Project\DestroyProjectIssue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectIssuesViewHelper;

class ProjectIssuesController extends Controller
{
    /**
     * Display the issue.
     *
     * @param Request $request
     * @param int $companyId
     * @param string $issueKey
     * @param string $issueSlug
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, string $issueKey, string $issueSlug)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $issue = ProjectIssue::where('key', $issueKey)
                ->where('slug', $issueSlug)
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        if ($issue->project->company_id != $loggedCompany->id) {
            return redirect('home');
        }

        if ($issue->is_separator) {
            return redirect('home');
        }

        // board comes from the CheckBoard middleware
        $board = $request->get('board');

        return Inertia::render('Company/Project/Boards/ProjectIssue/Show', [
            'tab' => 'boards',
            'data' => ProjectIssuesViewHelper::issueData($issue),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Save the issue.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $boardId
     * @param int $sprintId
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
            'is_separator' => $request->input('is_separator'),
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
                'is_separator' => $issue->is_separator,
                'type' => $issue->type ? [
                    'name' => $issue->type->name,
                    'icon_hex_color' => $issue->type->icon_hex_color,
                ] : null,
                'url' => [
                    'show' => route('projects.issues.show', [
                        'company' => $loggedCompany,
                        'key' => $issue->key,
                        'slug' => $issue->slug,
                    ]),
                    'destroy' => route('projects.issues.destroy', [
                        'company' => $loggedCompany,
                        'project' => $projectId,
                        'board' => $boardId,
                        'sprint' => $sprintId,
                        'issue' => $issue->id,
                    ]),
                ],
            ],
        ], 201);
    }

    /**
     * Destroy the issue.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $boardId
     * @param int $sprintId
     * @param int $issueId
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $boardId, int $sprintId, int $issueId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_sprint_id' => $sprintId,
            'project_issue_id' => $issueId,
        ];

        (new DestroyProjectIssue)->execute($data);

        return response()->json([
            'data' => true,
        ], 200);
    }
}
