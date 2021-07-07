<?php

namespace App\Http\Controllers\Company\Company\Group;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use App\Models\Company\Group;
use App\Helpers\InstanceHelper;
use App\Models\Company\Meeting;
use Illuminate\Http\JsonResponse;
use App\Helpers\NotificationHelper;
use App\Http\Controllers\Controller;
use App\Services\Company\Group\CreateMeeting;
use App\Services\Company\Group\DestroyMeeting;
use App\Services\Company\Group\CreateAgendaItem;
use App\Services\Company\Group\UpdateAgendaItem;
use App\Services\Company\Group\AddGuestToMeeting;
use App\Services\Company\Group\DestroyAgendaItem;
use App\Services\Company\Group\UpdateMeetingDate;
use App\Services\Company\Group\CreateMeetingDecision;
use App\Services\Company\Group\DestroyMeetingDecision;
use App\Services\Company\Group\RemoveGuestFromMeeting;
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
     *
     * @return \Illuminate\Http\RedirectResponse|Response
     */
    public function index(Request $request, int $companyId, int $groupId)
    {
        $company = InstanceHelper::getLoggedCompany();
        $employee = InstanceHelper::getLoggedEmployee();

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
            'data' => $meetings,
            'notifications' => NotificationHelper::getNotifications($employee),
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
     *
     * @return \Illuminate\Http\RedirectResponse
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
     *
     * @return \Illuminate\Http\RedirectResponse|Response
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
        $agenda = GroupMeetingsViewHelper::agenda($meeting, $loggedCompany);

        return Inertia::render('Company/Group/Meetings/Show', [
            'group' => $groupInfo,
            'meeting' => $meetingInfo,
            'agenda' => $agenda,
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
     *
     * @return JsonResponse
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

    /**
     * Get the list of potential participants for this meeting.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function search(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        $company = InstanceHelper::getLoggedCompany();

        try {
            $group = Group::where('company_id', $company->id)
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

        $potentialMembers = GroupMeetingsViewHelper::potentialGuests(
            $meeting,
            $company,
            $request->input('searchTerm')
        );

        return response()->json([
            'data' => $potentialMembers,
        ]);
    }

    /**
     * Add participant to the meeting.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function addParticipant(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $employee = (new AddGuestToMeeting)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'employee_id' => $request->input('id'),
        ]);

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 23),
                'was_a_guest' => true,
                'attended' => false,
            ],
        ]);
    }

    /**
     * Remove participant from the meeting.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function removeParticipant(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new RemoveGuestFromMeeting)->execute([
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

    /**
     * Set the meeting date.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function setDate(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $date = Carbon::createFromDate($request->input('year'), $request->input('month'), $request->input('day'));

        (new UpdateMeetingDate)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'date' => $date->format('Y-m-d'),
        ]);

        return response()->json([
            'data' => DateHelper::formatDate($date),
        ]);
    }

    /**
     * Destroy the meeting.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function destroy(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyMeeting)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }

    /**
     * Create an agenda item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function createAgendaItem(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $agendaItem = (new CreateAgendaItem)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'summary' => $request->input('summary'),
            'description' => $request->input('description'),
            'presented_by_id' => $request->input('presented_by_id'),
        ]);

        return response()->json([
            'data' => [
                'id' => $agendaItem->id,
                'summary' => $agendaItem->summary,
                'description' => $agendaItem->description,
                'position' => $agendaItem->position,
                'presenter' => $agendaItem->presenter ? [
                    'id' => $agendaItem->presenter->id,
                    'name' => $agendaItem->presenter->name,
                    'avatar' => ImageHelper::getAvatar($agendaItem->presenter, 23),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $agendaItem->presenter,
                    ]),
                ] : null,
            ],
        ]);
    }

    /**
     * Update the agenda item summary.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     * @param int $agendaItemId
     *
     * @return JsonResponse
     */
    public function updateAgendaItem(Request $request, int $companyId, int $groupId, int $meetingId, int $agendaItemId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $agendaItem = (new UpdateAgendaItem)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'agenda_item_id' => $agendaItemId,
            'summary' => $request->input('summary'),
            'description' => $request->input('description'),
            'presented_by_id' => $request->input('presented_by_id'),
        ]);

        return response()->json([
            'data' => [
                'id' => $agendaItem->id,
                'summary' => $agendaItem->summary,
                'description' => $agendaItem->description,
                'position' => $agendaItem->position,
                'presenter' => $agendaItem->presenter ? [
                    'id' => $agendaItem->presenter->id,
                    'name' => $agendaItem->presenter->name,
                    'avatar' => ImageHelper::getAvatar($agendaItem->presenter, 23),
                    'url' => route('employees.show', [
                        'company' => $loggedCompany,
                        'employee' => $agendaItem->presenter,
                    ]),
                ] : null,
            ],
        ]);
    }

    /**
     * Destroy the agenda item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     * @param int $agendaItemId
     */
    public function destroyAgendaItem(Request $request, int $companyId, int $groupId, int $meetingId, int $agendaItemId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyAgendaItem)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'agenda_item_id' => $agendaItemId,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }

    /**
     * Get the potential presenters of the agenda item.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     *
     * @return JsonResponse
     */
    public function getPresenters(Request $request, int $companyId, int $groupId, int $meetingId): JsonResponse
    {
        try {
            $meeting = Meeting::where('group_id', $groupId)
                ->findOrFail($meetingId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        try {
            Group::where('company_id', $companyId)
                ->findOrFail($groupId);
        } catch (ModelNotFoundException $e) {
            return redirect('home');
        }

        $presenters = GroupMeetingsViewHelper::potentialPresenters($meeting, InstanceHelper::getLoggedCompany());

        return response()->json([
            'data' => $presenters,
        ]);
    }

    /**
     * Create a decision.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     * @param int $agendaItemId
     *
     * @return JsonResponse
     */
    public function createDecision(Request $request, int $companyId, int $groupId, int $meetingId, int $agendaItemId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        $meetingDecision = (new CreateMeetingDecision)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'agenda_item_id' => $agendaItemId,
            'description' => $request->input('description'),
        ]);

        return response()->json([
            'data' => [
                'id' => $meetingDecision->id,
                'description' => $meetingDecision->description,
            ],
        ]);
    }

    /**
     * Destroy a decision.
     *
     * @param Request $request
     * @param int $companyId
     * @param int $groupId
     * @param int $meetingId
     * @param int $agendaItemId
     * @param int $decisionId
     *
     * @return JsonResponse
     */
    public function destroyDecision(Request $request, int $companyId, int $groupId, int $meetingId, int $agendaItemId, int $decisionId): JsonResponse
    {
        $loggedCompany = InstanceHelper::getLoggedCompany();
        $loggedEmployee = InstanceHelper::getLoggedEmployee();

        (new DestroyMeetingDecision)->execute([
            'company_id' => $loggedCompany->id,
            'author_id' => $loggedEmployee->id,
            'group_id' => $groupId,
            'meeting_id' => $meetingId,
            'agenda_item_id' => $agendaItemId,
            'meeting_decision_id' => $decisionId,
        ]);

        return response()->json([
            'data' => true,
        ]);
    }
}
