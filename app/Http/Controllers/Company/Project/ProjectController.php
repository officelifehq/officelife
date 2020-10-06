<?php

namespace App\Http\Controllers\Company\Project;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProject;

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

        return Inertia::render('Project/Show', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
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

        $project = (new CreateProject)->execute($request);

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
