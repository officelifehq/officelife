<?php

namespace App\Http\Controllers\Company\Company\Group;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Company\Group;
use App\Helpers\InstanceHelper;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Group\GroupShowViewHelper;

class GroupMeetingsController extends Controller
{
    /**
     * Display the Meetings page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
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

        $info = GroupShowViewHelper::information($group, $company);

        return Inertia::render('Company/Group/Meetings/Index', [
            'group' => $info,
            'tab' => 'meetings',
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Show the Create meeting page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     */
    public function create(Request $request, int $companyId, int $groupId)
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $group = Group::where('company_id', $company->id)
                ->findOrFail($groupId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $info = GroupShowViewHelper::information($group, $company);

        return Inertia::render('Company/Group/Meetings/Create', [
            'group' => $info,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }
}
