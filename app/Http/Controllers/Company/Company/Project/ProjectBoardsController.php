<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;

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
            'notifications' => NotificationHelper::getNotifications($loggedEmployee),
        ]);
    }
}
