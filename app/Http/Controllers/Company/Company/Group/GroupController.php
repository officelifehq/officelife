<?php

namespace App\Http\Controllers\Company\Company\Group;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Models\Company\Group;
use App\Helpers\InstanceHelper;
use App\Models\Company\Employee;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Group\CreateGroup;
use App\Http\ViewHelpers\Company\CompanyViewHelper;
use App\Http\ViewHelpers\Company\Group\GroupShowViewHelper;
use App\Http\ViewHelpers\Company\Project\ProjectViewHelper;
use App\Http\ViewHelpers\Company\Group\GroupCreateViewHelper;

class GroupController extends Controller
{
    /**
     * Display the list of groups.
     *
     * @param Request $request
     * @param int $companyId
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
     * Display the group.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @return Response
     */
    public function show(Request $request, int $companyId, int $groupId): Response
    {
        $company = InstanceHelper::getLoggedCompany();
        $group = Group::where('company_id', $company->id)
            ->findOrFail($groupId);

        return Inertia::render('Company/Group/Show', [
            'group' => GroupShowViewHelper::information($group, $company),
            'tab' => 'info',
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Display the create new group form.
     *
     * @param Request $request
     * @param int $companyId
     * @return Response
     */
    public function create(Request $request, int $companyId): Response
    {
        return Inertia::render('Company/Group/Create', [
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Search an employee to add as a team lead.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $employees = GroupCreateViewHelper::search($loggedCompany, $request->input('searchTerm'));

        return response()->json([
            'data' => $employees,
        ], 200);
    }

    /**
     * Actually create the new group.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $employees = null;
        if ($request->input('employees')) {
            $employees = [];
            foreach ($request->input('employees') as $employee) {
                array_push($employees, $employee['id']);
            }
        }

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'employees' => $employees,
        ];

        $group = (new CreateGroup)->execute($data);

        return response()->json([
            'data' => [
                'id' => $group->id,
                'url' => route('groups.show', [
                    'company' => $loggedCompany,
                    'group' => $group,
                ]),
            ],
        ], 201);
    }

    /**
     * Destroy the group.
     *
     * @param Request $request
     * @param int $companyId
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId): JsonResponse
    {
        $loggedEmployee = InstanceHelper::getLoggedEmployee();
        $loggedCompany = InstanceHelper::getLoggedCompany();

        $employees = null;
        if ($request->input('employees')) {
            $employees = [];
            foreach ($request->input('employees') as $employee) {
                array_push($employees, $employee['id']);
            }
        }

        $data = [
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'name' => $request->input('name'),
            'employees' => $employees,
        ];

        $group = (new CreateGroup)->execute($data);

        return response()->json([
            'data' => [
                'id' => $group->id,
                'url' => route('groups.show', [
                    'company' => $loggedCompany,
                    'group' => $group,
                ]),
            ],
        ], 201);
    }
}
