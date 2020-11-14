<?php

namespace App\Http\Controllers\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use App\Services\Company\Project\CreateProjectStatus;

class ProjectStatusController extends Controller
{
    /**
     * Display a Create status page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|Response
     */
    public function create(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();
        $project = Project::findOrFail($projectId);

        if (! $employee->isInProject($projectId) && $employee->permission_level > 200) {
            return redirect('home');
        }

        if ($project->lead) {
            if ($project->lead->id != $employee->id && $employee->permission_level > 200) {
                return redirect('home');
            }
        }

        return Inertia::render('Project/CreateStatus', [
            'project' => ProjectViewHelper::summary($project, $company),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Save the new project status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     */
    public function store(Request $request, int $companyId, int $projectId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'status' => $request->input('status'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        (new CreateProjectStatus)->execute($data);

        return response()->json([
            'data' => [
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $projectId,
                ]),
            ],
        ], 201);
    }
}
