<?php

namespace App\Http\Controllers\Company\Company\Project;

use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Company\Project\ProjectBoardsViewHelper;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Models\Company\Project;
use App\Services\Company\Project\CreateProjectBoard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProjectBoardsController extends Controller
{
    /**
     * Display the list of boards in the project.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Boards/Index', [
            'tab' => 'boards',
            'project' => ProjectViewHelper::info($project),
            'data' => ProjectBoardsViewHelper::index($project),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Add a board to the project.
     */
    public function store(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'name' => $request->input('name'),
        ];

        $board = (new CreateProjectBoard)->execute($data);

        return response()->json([
            'data' => [
                'id' => $board->id,
                'name' => $board->name,
                'url' => route('projects.boards.show', [
                    'company' => $loggedCompany,
                    'project' => $projectId,
                    'board' => $board,
                ]),
            ],
        ], 201);
    }

    /**
     * Display the board summary.
     *
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function show(Request $request, int $companyId, int $projectId, int $boardId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // board comes from the CheckBoard middleware
        $board = $request->get('board');

        return Inertia::render('Company/Project/Boards/Show', [
            'tab' => 'boards',
            'project' => ProjectViewHelper::info($board->project),
            'data' => ProjectBoardsViewHelper::backlog($board->project, $board, $loggedEmployee),
            'issueTypes' => ProjectBoardsViewHelper::issueTypes($loggedCompany),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
