<?php

namespace App\Http\ViewHelpers\Company\Group;

use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\Meeting;

class GroupMeetingsViewHelper
{
    /**
     * Get all the meetings in the group.
     *
     * @param Group $group
     * @return array
     */
    public static function index(Group $group): array
    {
        return [
            'url_new' => route('groups.meetings.new', [
                'company' => $group->company_id,
                'group' => $group,
            ]),
        ];
    }

    /**
     * Get information of a specific meeting.
     *
     * @param Meeting $meeting
     * @param Company $company
     * @return array
     */
    public static function show(Meeting $meeting, Company $company): array
    {
        $participants = $meeting->employees()
            ->orderBy('last_name', 'asc')
            ->get();

        $membersCollection = collect([]);
        foreach ($participants as $employee) {
            $membersCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
                'avatar' => ImageHelper::getAvatar($employee, 23),
                'attended' => (bool) $employee->pivot->attended,
                'was_a_guest' => (bool) $employee->pivot->was_a_guest,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $employee,
                ]),
            ]);
        }

        return [
            'id' => $meeting->id,
            'participants' => $membersCollection,
        ];
    }
}
