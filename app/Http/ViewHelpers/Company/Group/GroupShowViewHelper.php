<?php

namespace App\Http\ViewHelpers\Company\Group;

use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Company;

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
            'members' => $membersCollection,
            'url_edit' => route('groups.edit', [
                'company' => $company,
                'group' => $group,
            ]),
            'url_delete' => route('groups.destroy', [
                'company' => $company,
                'group' => $group,
            ]),
        ];
    }
}
