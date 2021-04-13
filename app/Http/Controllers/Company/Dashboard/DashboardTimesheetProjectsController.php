<?php

namespace App\Http\Controllers\Company\Dashboard;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProject;
use App\Exceptions\ProjectCodeAlreadyExistException;

class DashboardTimesheetProjectsController extends Controller
{
    /**
     * Display the create new project form.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     *
     * @route dashboard.timesheet.projects.create
     * @url GET /1/dashboard/timesheet/projects/create
     */
    public function create(Request $request, int $companyId): Response
    {
        return Inertia::render('Dashboard/Timesheet/Project/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Actually create the new project.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     *
     * @route dashboard.timesheet.projects.store
     * @url POST /1/dashboard/timesheet/projects
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
                'url' => route('dashboard.timesheet.index', [
                    'company' => $company,
                ]),
            ],
        ], 201);
    }
}
