<?php

namespace App\Http\ViewHelpers\Company\Group;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;
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
        return $group->employees()
            ->notLocked()
            ->with('position')
            ->orderBy('pivot_created_at', 'desc')
            ->get()
            ->map(function ($member) use ($group) {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'avatar' => ImageHelper::getAvatar($member),
                    'added_at' => DateHelper::formatDate($member->pivot->created_at),
                    'position' => (! $member->position) ? null : [
                        'id' => $member->position->id,
                        'title' => $member->position->title,
                    ],
                    'url' => route('employees.show', [
                        'company' => $group->company_id,
                        'employee' => $member,
                    ]),
                ];
            });
    }

    /**
     * Returns the potential employees that can be assigned as members of the
     * group, matching the name.
     * This filters out the current members of the group (doh).
     *
     * @param Group $group
     * @param Company $company
     * @param string $criteria
     * @return Collection
     */
    public static function potentialMembers(Company $company, Group $group, string $criteria): Collection
    {
        $potentialEmployees = $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get();

        $currentMembers = $group->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id')
            ->get();

        $potentialEmployees = $potentialEmployees->diff($currentMembers);

        $potentialEmployeesCollection = collect([]);
        foreach ($potentialEmployees as $potential) {
            $potentialEmployeesCollection->push([
                'id' => $potential->id,
                'name' => $potential->name,
                'avatar' => ImageHelper::getAvatar($potential, 64),
            ]);
        }

        return $potentialEmployeesCollection;
    }
}
