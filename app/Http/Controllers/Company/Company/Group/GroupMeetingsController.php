<?php

namespace App\Http\Controllers\Company\Company\Group;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Company\Group;
use App\Helpers\InstanceHelper;
use App\Models\Company\Meeting;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Group\CreateMeeting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\ViewHelpers\Company\Group\GroupShowViewHelper;
use App\Http\ViewHelpers\Company\Group\GroupMeetingsViewHelper;
use App\Services\Company\Group\ToggleEmployeeParticipationInMeeting;

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
        $meetings = GroupMeetingsViewHelper::index($group);

        return Inertia::render('Company/Group/Meetings/Index', [
            'group' => $info,
            'tab' => 'meetings',
            'meetings' => $meetings,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Create the meeting page and redirects to it.
     * A meeting doesn't have a dedicated Create screen - viewing, creating
     * and editing is done on the same page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     */
    public function create(Request $request, int $companyId, int $groupId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        try {
            $group = Group::where('company_id', $loggedCompany->id)
                ->findOrFail($groupId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $meeting = (new CreateMeeting)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $group->id,
        ]);

        return redirect()->route('groups.meetings.show', [
            'company' => $loggedCompany->id,
            'group' => $group->id,
            'meeting' => $meeting->id,
        ]);
    }

    /**
     * Show the meeting page.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     */
    public function show(Request $request, int $companyId, int $groupId, int $meetingId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();

        try {
            $group = Group::where('company_id', $loggedCompany->id)
                ->findOrFail($groupId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            $meeting = Meeting::where('group_id', $group->id)
                ->findOrFail($meetingId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $groupInfo = GroupShowViewHelper::information($group, $loggedCompany);
        $meetingInfo = GroupMeetingsViewHelper::show($meeting, $loggedCompany);

        return Inertia::render('Company/Group/Meetings/Show', [
            'group' => $groupInfo,
            'meeting' => $meetingInfo,
            'notifications' => NotificationHelper::getNotifications(InstanceHelper::getLoggedEmployee()),
        ]);
    }

    /**
     * Toggle the participation for a person in the meeting.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     */
    public function toggleParticipant(Request $request, int $companyId, int $groupId, int $meetingId)
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new ToggleEmployeeParticipationInMeeting)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'employee_id' => $request->input('id'),
        ]);

        return response()->json([
            'data' => true,
        ]);
    }
}
