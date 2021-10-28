<?php

namespace App\Http\Controllers\Company\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\StringHelper;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CloseProject;
use App\Services\Company\Project\PauseProject;
use App\Services\Company\Project\StartProject;
use App\Services\Company\Project\CreateProject;
use App\Services\Company\Project\DestroyProject;
use App\Services\Company\Project\ClearProjectLead;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use App\Services\Company\Project\CreateProjectLink;
use App\Services\Company\Project\UpdateProjectLead;
use App\Exceptions\ProjectCodeAlreadyExistException;
use App\Services\Company\Project\DestroyProjectLink;
use App\Services\Company\Project\CreateProjectStatus;
use App\Services\Company\Project\UpdateProjectDescription;
use App\Services\Company\Project\UpdateProjectInformation;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;

class ProjectController extends Controller
{
    /**
     * Display the list of projects.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $statistics = CompanyViewHelper::information($company);

        return Inertia::render('Company/Project/Index', [
            'statistics' => $statistics,
            'tab' => 'projects',
            'projects' => ProjectViewHelper::index($company),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return Response
     */
    public function show(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Show', [
            'project' => ProjectViewHelper::info($project),
            'projectDetails' => ProjectViewHelper::summary($project, $company, $employee),
            'permissions' => ProjectViewHelper::permissions($project, $employee),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Start the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function start(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
        ];

        $project = (new StartProject)->execute($data);

        return response()->json([
            'status' => $project->status,
        ], 201);
    }

    /**
     * Pause the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function pause(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
        ];

        $project = (new PauseProject)->execute($data);

        return response()->json([
            'status' => $project->status,
        ], 201);
    }

    /**
     * Close the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function close(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
        ];

        $project = (new CloseProject)->execute($data);

        return response()->json([
            'status' => $project->status,
        ], 201);
    }

    /**
     * Display the Edit project page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return Response
     */
    public function edit(Request $request, int $companyId, int $projectId): Response
    {
        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Edit', [
            'project' => ProjectViewHelper::edit($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Update the project information.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'short_code' => $request->input('short_code'),
            'summary' => $request->input('summary'),
        ];

        try {
            $project = (new UpdateProjectInformation)->execute($data);
        } catch (ProjectCodeAlreadyExistException $e) {
            return response()->json([
                'message' => trans('app.error_project_code_already_exists'),
            ], 500);
        }

        return response()->json([
            'data' => [
                'id' => $project->id,
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $project,
                ]),
            ],
        ], 201);
    }

    /**
     * Update the project description.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function description(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'description' => $request->input('description'),
        ];

        $project = (new UpdateProjectDescription)->execute($data);

        return response()->json([
            'data' => [
                'raw_description' => is_null($project->description) ? null : $project->description,
                'parsed_description' => is_null($project->description) ? null : StringHelper::parse($project->description),
            ],
        ], 201);
    }

    /**
     * Show the delete project view.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function delete(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Delete', [
            'project' => [
                'id' => $project->id,
            ],
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Actually delete project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        (new DestroyProject)->execute([
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $project->id,
        ]);

        return response()->json([
            'data' => true,
        ], 200);
    }

    /**
     * Assign project lead.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function assign(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        $lead = (new UpdateProjectLead)->execute([
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $project->id,
            'employee_id' => $request->input('employeeId'),
        ]);

        return response()->json([
            'data' => [
                'id' => $lead->id,
                'name' => $lead->name,
                'avatar' => ImageHelper::getAvatar($lead, 35),
                'position' => (! $lead->position) ? null : [
                    'id' => $lead->position->id,
                    'title' => $lead->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $lead,
                ]),
            ],
        ], 200);
    }

    /**
     * Clear project lead.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function clear(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        $project = (new ClearProjectLead)->execute([
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $project->id,
        ]);

        return response()->json([
            'data' => null,
        ], 200);
    }

    /**
     * Display the create new project form.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return Response
     */
    public function create(Request $request, int $companyId): Response
    {
        return Inertia::render('Company/Project/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Search an employee to assign as project lead.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employees = ProjectViewHelper::searchProjectLead($loggedCompany, $request->input('searchTerm', ''));

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Actually create the new project.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_lead_id' => $request->input('projectLead') ? $request->input('projectLead')['id'] : null,
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'short_code' => $request->input('short_code'),
            'summary' => $request->input('summary'),
            'description' => $request->input('description'),
        ];

        try {
            $project = (new CreateProject)->execute($data);
        } catch (ProjectCodeAlreadyExistException $e) {
            return response()->json([
                'message' => trans('app.error_project_code_already_exists'),
            ], 500);
        }

        return response()->json([
            'data' => [
                'id' => $project->id,
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $project,
                ]),
            ],
        ], 201);
    }

    /**
     * Create a new project link.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function createLink(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'type' => $request->input('type'),
            'label' => ($request->input('label')) ? $request->input('label') : null,
            'url' => $request->input('url'),
        ];

        $link = (new CreateProjectLink)->execute($data);

        return response()->json([
            'data' => [
                'id' => $link->id,
                'type' => $link->type,
                'label' => $link->label,
                'url' => $link->url,
            ],
        ], 201);
    }

    /**
     * Destroy a new project link.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $linkId
     *
     * @return JsonResponse
     */
    public function destroyLink(Request $request, int $companyId, int $projectId, int $linkId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_link_id' => $linkId,
        ];

        (new DestroyProjectLink)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }

    /**
     * Display a Create status page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function createStatus(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        if (! $employee->isInProject($projectId) && $employee->permission_level > 200) {
            return redirect('home');
        }

        if ($project->lead) {
            if ($project->lead->id != $employee->id && $employee->permission_level > 200) {
                return redirect('home');
            }
        }

        return Inertia::render('Company/Project/Status/Create', [
            'project' => ProjectViewHelper::summary($project, $company, $employee),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Save the new project status.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function postStatus(Request $request, int $companyId, int $projectId)
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
