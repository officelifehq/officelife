<?php

namespace App\Http\Controllers\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
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
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use App\Services\Company\Project\CreateProjectLink;
use App\Services\Company\Project\UpdateProjectLead;
use App\Exceptions\ProjectCodeAlreadyExistException;
use App\Services\Company\Project\DestroyProjectLink;
use App\Services\Company\Project\CreateProjectStatus;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Project\UpdateProjectDescription;
use App\Services\Company\Project\UpdateProjectInformation;

class ProjectController extends Controller
{
    /**
     * Display the list of projets.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function index(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Index', [
            'tab' => 'summary',
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
     * @return Response
     */
    public function show(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();
        $project = Project::findOrFail($projectId);

        return Inertia::render('Project/Show', [
            'project' => ProjectViewHelper::summary($project, $company),
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
     * @return JsonResponse
     */
    public function start(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
        ];

        $project = (new StartProject)->execute($request);

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
     * @return JsonResponse
     */
    public function pause(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
        ];

        $project = (new PauseProject)->execute($request);

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
     * @return JsonResponse
     */
    public function close(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
        ];

        $project = (new CloseProject)->execute($request);

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
     * @return Response
     */
    public function edit(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $project = Project::findOrFail($projectId);

        return Inertia::render('Project/Edit', [
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
     * @return JsonResponse
     */
    public function update(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'summary' => $request->input('summary'),
        ];

        try {
            $project = (new UpdateProjectInformation)->execute($request);
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
     * @return JsonResponse
     */
    public function description(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'description' => $request->input('description'),
        ];

        $project = (new UpdateProjectDescription)->execute($request);

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
     * @return Response
     */
    public function delete(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        return Inertia::render('Project/Delete', [
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
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

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
     * @return JsonResponse
     */
    public function assign(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

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
                'avatar' => $lead->avatar,
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
     * @return JsonResponse
     */
    public function clear(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $project = Project::where('company_id', $company->id)
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

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
     * Display the project messages.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @return Response
     */
    public function messages(Request $request, int $companyId, int $projectId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Messages', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the project messages.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $messageId
     * @return Response
     */
    public function message(Request $request, int $companyId, int $projectId, int $messageId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Message', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the create new project form.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function create(Request $request, int $companyId): Response
    {
        $company = InstanceHelper::getLoggedCompany();

        return Inertia::render('Project/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Search an employee to assign as project lead.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $potentialEmployees = Employee::search(
            $request->input('searchTerm'),
            $companyId,
            10,
            'created_at desc',
            'and locked = false',
        );

        $employees = collect([]);
        foreach ($potentialEmployees as $employee) {
            $employees->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => $employee->avatar,
            ]);
        }

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Actually create the new project.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_lead_id' => $request->input('projectLead') ? $request->input('projectLead')['id'] : null,
            'name' => $request->input('name'),
            'code' => $request->input('code'),
            'summary' => $request->input('summary'),
            'description' => $request->input('description'),
        ];

        try {
            $project = (new CreateProject)->execute($request);
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
     * @return JsonResponse
     */
    public function createLink(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'type' => $request->input('type'),
            'label' => ($request->input('label')) ? $request->input('label') : null,
            'url' => $request->input('url'),
        ];

        $link = (new CreateProjectLink)->execute($request);

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
     * @return JsonResponse
     */
    public function destroyLink(Request $request, int $companyId, int $projectId, int $linkId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_link_id' => $linkId,
        ];

        (new DestroyProjectLink)->execute($request);

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
     */
    public function createStatus(Request $request, int $companyId, int $projectId)
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
    public function postStatus(Request $request, int $companyId, int $projectId)
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $company = InstanceHelper::getLoggedCompany();

        $request = [
            'company_id' => $company->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'status' => $request->input('status'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ];

        (new CreateProjectStatus)->execute($request);

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
