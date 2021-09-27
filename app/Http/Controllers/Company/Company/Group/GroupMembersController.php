<?php

namespace App\Http\Controllers\Company\Company\Group;

use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Models\Company\Group;
use App\Helpers\InstanceHelper;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Group\AddEmployeeToGroup;
use App\Services\Company\Group\RemoveEmployeeFromGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Group\GroupShowViewHelper;
use App\Http\ViewHelpers\Company\Group\GroupMembersViewHelper;

class GroupMembersController extends Controller
{
    /**
     * Display the list of members in the group.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $groupId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $group = Group::where('company_id', $company->id)
                ->findOrFail($groupId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $members = GroupMembersViewHelper::members($group);
        $info = GroupShowViewHelper::information($group, $company);

        return Inertia::render('Company/Group/Members/Index', [
            'members' => $members,
            'tab' => 'members',
            'group' => $info,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Get the list of potential new members for this group.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     *
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $groupId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $group = Group::where('company_id', $company->id)
                ->findOrFail($groupId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $potentialMembers = GroupMembersViewHelper::potentialMembers(
            $company,
            $group,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $potentialMembers,
        ]);
    }

    /**
     * Add the member to the group.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     *
     * @return JsonResponse
     */
    public function store(Request $request, int $companyId, int $groupId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $group = Group::where('company_id', $loggedCompany->id)
            ->findOrFail($groupId);

        $employee = (new AddEmployeeToGroup)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $group->id,
            'employee_id' => $request->input('employee'),
            'role' => null,
        ]);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 32),
            ],
        ]);
    }

    /**
     * Remove the member from the group.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     *
     * @return JsonResponse
     */
    public function remove(Request $request, int $companyId, int $groupId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $group = Group::where('company_id', $loggedCompany->id)
            ->findOrFail($groupId);

        (new RemoveEmployeeFromGroup)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'employee_id' => $request->input('employee'),
            'group_id' => $group->id,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }
}
