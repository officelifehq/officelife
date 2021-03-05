<?php

namespace App\Http\ViewHelpers\Company\Group;

use App\Helpers\DateHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class GroupMembersViewHelper
{
    /**
     * Array containing the information all the members in the group.
     *
     * @param Group $group
     * @return Collection
     */
    public static function members(Group $group): Collection
    {
        $members = $group->employees()
            ->where('locked', false)
            ->with('position')
            ->orderBy('pivot_created_at', 'desc')
            ->get();

        $membersCollection = collect([]);
        foreach ($members as $member) {
            $membersCollection->push([
                'id' => $member->id,
                'name' => $member->name,
                'avatar' => $member->avatar,
                'added_at' => DateHelper::formatDate($member->pivot->created_at),
                'position' => (! $member->position) ? null : [
                    'id' => $member->position->id,
                    'title' => $member->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $group->company_id,
                    'employee' => $member,
                ]),
            ]);
        }

        return $membersCollection;
    }

    /**
     * Returns the potential employees that can be assigned as members of the
     * group, matching the name.
     * This filters out the current members of the group (doh).
     *
     * @param string $employeeName
     * @param Group $group
     * @param Company $company
     * @return Collection
     */
    public static function potentialMembers(string $employeeName, Group $group, Company $company): Collection
    {
        $potentialEmployees = Employee::search(
            $employeeName,
            $company->id,
            10,
            'created_at desc',
            'and locked = 0',
        );

        $currentMembers = $group->employees;

        $potentialMembers = $potentialEmployees->diff($currentMembers);

        $potentialMembersCollection = collect([]);
        foreach ($potentialMembers as $potential) {
            $potentialMembersCollection->push([
                'id' => $potential->id,
                'name' => $potential->name,
                'avatar' => $potential->avatar,
            ]);
        }

        return $potentialMembersCollection;
    }
}
