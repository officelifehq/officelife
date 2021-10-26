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
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Project\CreateProjectDecision;
use App\Services\Company\Project\DestroyProjectDecision;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Http\ViewHelpers\Company\Project\ProjectDecisionsViewHelper;

class ProjectDecisionsController extends Controller
{
    /**
     * Display the list of decisions taken in the project.
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
        $employee = InstanceHelper::getLoggedEmployee();

        // project comes from the CheckProject middleware
        $project = $request->get('project');

        return Inertia::render('Company/Project/Decisions/Index', [
            'tab' => 'decisions',
            'project' => ProjectViewHelper::info($project),
            'decisions' => ProjectDecisionsViewHelper::decisions($project, $employee),
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Delete a decision.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $projectId
     * @param int $decisionId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $projectId, int $decisionId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'project_decision_id' => $decisionId,
        ];

        (new DestroyProjectDecision)->execute($data);

        return response()->json([
            'data' => true,
        ], 201);
    }

    /**
     * Search an employee that made a decision.
     *
     * @param Request $request
     * @param int $companyId
     *
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employees = ProjectDecisionsViewHelper::searchDeciders($loggedCompany, $request->input('searchTerm'));

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Add a decision to the project.
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

        $employees = null;

        // create an array of ids of employees
        if ($request->input('employees')) {
            $employees = [];
            foreach ($request->input('employees') as $employee) {
                array_push($employees, $employee['id']);
            }
        }

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'project_id' => $projectId,
            'title' => $request->input('title'),
            'decided_at' => Carbon::now()->format('Y-m-d'),
            'deciders' => $employees,
        ];

        $projectDecision = (new CreateProjectDecision)->execute($data);

        $decidersCollection = collect([]);
        foreach ($projectDecision->deciders as $decider) {
            $decidersCollection->push([
                'id' => $decider->id,
                'name' => $decider->name,
                'avatar' => ImageHelper::getAvatar($decider, ),
                'url' => route('employees.show', [
                    'company' => $loggedCompany,
                    'employee' => $decider,
                ]),
            ]);
        }

        return response()->json([
            'data' => [
                'id' => $projectDecision->id,
                'title' => $projectDecision->title,
                'decided_at' => DateHelper::formatDate($projectDecision->decided_at, $loggedEmployee->timezone),
                'deciders' => $decidersCollection,
            ],
        ], 201);
    }
}
