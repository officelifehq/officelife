<?php

namespace App\Http\ViewHelpers\Project;

use App\Helpers\DateHelper;
use App\Models\Company\Project;

class ProjectMembersViewHelper
{
    /**
     * Array containing the information all the members in the project.
     *
     * @param Project $project
     * @return array
     */
    public static function members(Project $project): array
    {
        $members = $project->employees()
            ->where('locked', false)
            ->orderBy('pivot_created_at', 'desc')
            ->get();

        $membersCollection = collect([]);
        foreach ($members as $member) {
            $membersCollection->push([
                'id' => $member->id,
                'name' => $member->name,
                'avatar' => $member->avatar,
                'role' => $member->pivot->role,
                'added_at' => DateHelper::formatDate($member->pivot->created_at),
                'position' => (! $member->position) ? null : [
                    'id' => $member->position->id,
                    'title' => $member->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $project->company_id,
                    'employee' => $member,
                ]),
            ]);
        }

        return [
            'members' => $membersCollection,
        ];
    }

    /**
     * Array containing the information about the project itself.
     *
     * @param Project $project
     * @return array
     */
    public static function info(Project $project): array
    {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->code,
            'summary' => $project->summary,
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

        // all the roles in the project
        $roles = collect([]);
        foreach ($currentMembers as $member) {
            if ($member->pivot->role) {
                $roles->push([
                    'id' => $member->id,
                    'role' => $member->pivot->role,
                ]);
            }
        }

        $roles = $roles->unique('role');

        return [
            'potential_members' => $potentialMembersCollection,
            'roles' => $roles,
        ];
    }
}
