<?php

namespace App\Http\ViewHelpers\Company\Group;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GroupShowViewHelper
{
    /**
     * Get all the information about a group.
     *
     * @param Group $group
     * @param Company $company
     * @return array
     */
    public static function information(Group $group, Company $company): array
    {
        $groupMembers = $group->employees()
            ->notLocked()
            ->orderBy('last_name', 'asc')
            ->get();

        $membersCollection = collect([]);
        foreach ($groupMembers as $employee) {
            $membersCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 32),
                'position' => (! $employee->position) ? null : [
                    'id' => $employee->position->id,
                    'title' => $employee->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return [
            'id' => $group->id,
            'name' => $group->name,
            'mission' => $group->mission ? StringHelper::parse($group->mission) : null,
            'members' => $membersCollection,
            'url_edit' => route('groups.edit', [
                'company' => $company,
                'group' => $group,
            ]),
            'url_delete' => route('groups.delete', [
                'company' => $company,
                'group' => $group,
            ]),
        ];
    }

    /**
     * Get the latest 3 meetings in the group.
     *
     * @param Group $group
     * @return Collection
     */
    public static function meetings(Group $group): Collection
    {
        $meetings = $group->meetings()
            ->orderBy('happened_at', 'desc')
            ->with('employees')
            ->take(3)
            ->get();

        $meetingsCollection = collect([]);
        foreach ($meetings as $meeting) {
            $members = $meeting->employees()
                ->wherePivot('attended', true)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $totalMembersCount = $meeting->employees()->wherePivot('attended', true)->count();
            $totalMembersCount = $totalMembersCount - $members->count();

            $membersCollection = collect([]);
            foreach ($members as $member) {
                $membersCollection->push([
                    'id' => $member->id,
                    'avatar' => ImageHelper::getAvatar($member, 25),
                    'url' => route('employees.show', [
                        'company' => $group->company_id,
                        'employee' => $member,
                    ]),
                ]);
            }

            $meetingsCollection->push([
                'id' => $meeting->id,
                'happened_at' => trans('group.meeting_index_item_title', ['date' => DateHelper::formatDate($meeting->happened_at)]),
                'url' => route('groups.meetings.show', [
                    'company' => $group->company_id,
                    'group' => $group,
                    'meeting' => $meeting,
                ]),
                'preview_members' => $membersCollection,
                'remaining_members_count' => $totalMembersCount,
            ]);
        }

        return $meetingsCollection;
    }

    /**
     * Get the statistics of the group.
     *
     * @param Group $group
     * @return array
     */
    public static function stats(Group $group): array
    {
        $numberOfMeetings = $group->meetings()->count();

        $allDatesOfMeetings = DB::table('meetings')
            ->where('group_id', $group->id)
            ->orderBy('happened_at', 'asc')
            ->select('happened_at')
            ->get()
            ->pluck('happened_at')
            ->toArray();

        $datesCollection = collect();
        for ($i = 0; $i < count($allDatesOfMeetings) - 1; $i++) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $allDatesOfMeetings[$i]);
            $nextDate = Carbon::createFromFormat('Y-m-d H:i:s', $allDatesOfMeetings[$i + 1]);

            $numberOfDays = $date->diffInDays($nextDate);
            $datesCollection->push($numberOfDays);
        }

        $frequency = null;
        if (count($allDatesOfMeetings) >= 2) {
            $frequency = $datesCollection->avg();
        }

        return [
            'number_of_meetings' => $numberOfMeetings,
            'frequency' => round($frequency),
        ];
    }

    /**
     * Get the information about the group, required for editing it.
     *
     * @param Group $group
     * @param Company $company
     * @return array
     */
    public static function edit(Group $group): array
    {
        return [
            'id' => $group->id,
            'name' => $group->name,
            'mission' => $group->mission,
        ];
    }
}
