<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Models\Company\Project;

class ProjectMembersViewHelper
{
    /**
     * Array containing the information all the members in the project, and all
     * the roles.
     *
     * @param Project $project
     * @return array
     */
    public static function members(Project $project): array
    {
        $members = $project->employees()
            ->where('locked', false)
            ->with('position')
            ->orderBy('pivot_created_at', 'desc')
            ->get();

        $membersCollection = collect([]);
        $roles = collect([]);
        foreach ($members as $member) {
            $membersCollection->push([
                'id' => $member->id,
                'name' => $member->name,
                'avatar' => ImageHelper::getAvatar($member, 64),
                'role' => $member->pivot->role,
                'added_at' => DateHelper::formatDate($member->pivot->created_at, $member->timezone),
                'position' => (! $member->position) ? null : [
                    'id' => $member->position->id,
                    'title' => $member->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $project->company_id,
                    'employee' => $member,
                ]),
            ]);

            if ($member->pivot->role) {
                $roles->push([
                    'id' => $member->id,
                    'role' => $member->pivot->role,
                ]);
            }
        }

        // filter the unique roles in the collection
        $roles = $roles->unique('role')->sortBy('role');

        return [
            'members' => $membersCollection,
            'roles' => $roles,
        ];
    }

    /**
     * Returns the potential employees that can be assigned as members.
     * This filters out the current members of the project (doh).
     * It also contains all the current roles currently used in the project.
     *
     * @param Project $project
     * @return array
     */
    public static function potentialMembers(Project $project): array
    {
        $company = $project->company;
        $employees = $company->employees()
            ->where('locked', false)
            ->get();

        $currentMembers = $project->employees;

        $potentialMembers = $employees->diff($currentMembers);

        $potentialMembersCollection = collect([]);
        foreach ($potentialMembers as $potential) {
            $potentialMembersCollection->push([
                'value' => $potential->id,
                'label' => $potential->name,
            ]);
        }

        return [
            'potential_members' => $potentialMembersCollection,
        ];
    }
}
