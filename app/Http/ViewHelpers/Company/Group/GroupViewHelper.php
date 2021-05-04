<?php

namespace App\Http\ViewHelpers\Company\Group;

use Carbon\Carbon;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class GroupViewHelper
{
    /**
     * Get all the information about the groups in the company.
     *
     * @param Group $group
     * @param Company $company
     * @return array
     */
    public static function index(Company $company): array
    {
        $groups = $company->groups()
            ->with('employees')
            ->orderBy('id', 'desc')->get();

        $groupsCollection = collect([]);
        foreach ($groups as $group) {
            $members = $group->employees()
                ->inRandomOrder()
                ->take(3)
                ->get();

            $totalMembersCount = $group->employees()->count();
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

            $groupsCollection->push([
                'id' => $group->id,
                'name' => $group->name,
                'mission' => $group->mission,
                'preview_members' => $membersCollection,
                'remaining_members_count' => $totalMembersCount,
                'url' => route('groups.show', [
                    'company' => $company,
                    'group' => $group,
                ]),
            ]);
        }

        return [
            'data' => $groupsCollection,
            'url_create' => route('groups.new', [
                'company' => $company,
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
                ->inRandomOrder()
                ->take(3)
                ->get();

            $totalMembersCount = $meeting->employees()->count();
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
            'frequency' => $frequency,
        ];
    }
}
