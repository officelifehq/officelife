<?php

namespace App\Http\ViewHelpers\Company\Group;

use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\Meeting;
use Illuminate\Support\Collection;

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

    /**
     * Get potential guests of this meeting.
     *
     * @param Meeting $meeting
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function potentialGuests(Meeting $meeting, Company $company, string $criteria): Collection
    {
        $members = $meeting->employees()
            ->select('id', 'first_name', 'last_name')
            ->orderBy('last_name', 'asc')
            ->get();

        $potentialGuests = $company->employees()
            ->select('id', 'first_name', 'last_name')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $potentialGuests->diff($members);

        $employeesCollection = collect([]);
        foreach ($potentialGuests as $employee) {
            $employeesCollection->push([
                'id' => $employee->id,
                'name' => $employee->name,
            ]);
        }

        return $employeesCollection;
    }
}
