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
use App\Services\Company\Project\CreateProjectBoard;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Http\ViewHelpers\Company\Project\ProjectBoardsViewHelper;

class ProjectBoardsController extends Controller
{
    /**
     * Display the list of boards in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $loggedCompany->id)
                ->with('boards')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Company/Project/Boards/Index', [
            'tab' => 'boards',
            'project' => ProjectViewHelper::info($project),
            'data' => ProjectBoardsViewHelper::index($project),
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }

    /**
     * Add a board to the project.
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
}
