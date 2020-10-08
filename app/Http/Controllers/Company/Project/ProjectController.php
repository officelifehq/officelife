<?php

namespace App\Http\Controllers\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProject;
use App\Services\Company\Project\DestroyProject;
use App\Http\ViewHelpers\Project\ProjectViewHelper;
use App\Exceptions\ProjectCodeAlreadyExistException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        $project = Project::findOrFail($projectId);

        return Inertia::render('Project/Show', [
            'project' => ProjectViewHelper::summary($project, $company),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
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
}
