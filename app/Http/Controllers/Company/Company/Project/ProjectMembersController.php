<?php

namespace App\Http\Controllers\Company\Company\Project;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Helpers\InstanceHelper;
use App\Models\Company\Project;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\AddEmployeeToProject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Services\Company\Project\RemoveEmployeeFromProject;
use App\Http\ViewHelpers\Company\Project\ProjectMembersViewHelper;

class ProjectMembersController extends Controller
{
    /**
     * Display the list of members in the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $projectId)
    {
        $company = InstanceHelper::getLoggedCompany();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Members/Index', [
            'tab' => 'members',
            'project' => ProjectViewHelper::info($project),
            'members' => ProjectMembersViewHelper::members($project),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Returns all potential members, displayed in the Add member modal.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     *
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $projectId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $project = Project::where('company_id', $company->id)
                ->with('employees')
                ->findOrFail($projectId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $potentialMembers = ProjectMembersViewHelper::potentialMembers($project);

        return response()->json([
            'data' => $potentialMembers,
        ], 200);
    }

    /**
     * Add an employee to the project.
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
            'employee_id' => $request->input('employee_id'),
            'role' => $request->input('role'),
        ];

        $employee = (new AddEmployeeToProject)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 64),
                'role' => $request->input('role'),
                'added_at' => DateHelper::formatDate(Carbon::now(), $loggedEmployee->timezone),
                'position' => (! $employee->position) ? null : [
                    'id' => $employee->position->id,
                    'title' => $employee->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $loggedCompany,
                    'employee' => $employee,
                ]),
            ],
        ], 201);
    }

    /**
     * Remove an employee from the project.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $employeeId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $employeeId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'employee_id' => $employeeId,
        ];

        (new RemoveEmployeeFromProject)->execute($data);

        return response()->json([
            'data' => [
                'id' => $employeeId,
            ],
        ], 201);
    }
}
