<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use Illuminate\Support\Collection;

class ProjectViewHelper
{
    /**
     * Array containing the information all the projects in the company.
     *
     * @param Company $company
     * @return array
     */
    public static function index(Company $company): array
    {
        $projects = $company->projects()->orderBy('id', 'desc')->get();

        $projectsCollection = collect([]);
        foreach ($projects as $project) {
            $projectsCollection->push([
                'id' => $project->id,
                'name' => $project->name,
                'code' => $project->code,
                'summary' => $project->summary,
                'status' => $project->status,
                'url' => route('projects.show', [
                    'company' => $company,
                    'project' => $project,
                ]),
            ]);
        }

        return [
            'projects' => $projectsCollection,
        ];
    }

    /**
     * Array containing the information about the project displayed on the
     * summary page.
     *
     * @param Project $project
     * @param Company $company
     * @param Employee $employee
     * @return array
     */
    public static function summary(Project $project, Company $company, Employee $employee): array
    {
        $lead = $project->lead;
        $latestStatus = $project->statuses()->with('author')->latest()->first();

        $links = $project->links;
        $linkCollection = collect([]);
        foreach ($links as $link) {
            $linkCollection->push([
                'id' => $link->id,
                'type' => $link->type,
                'label' => $link->label,
                'url' => $link->url,
            ]);
        }

        $author = null;
        if (! is_null($latestStatus)) {
            $author = $latestStatus->author;
        }

        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->code,
            'summary' => $project->summary,
            'status' => $project->status,
            'raw_description' => is_null($project->description) ? null : $project->description,
            'parsed_description' => is_null($project->description) ? null : StringHelper::parse($project->description),
            'latest_update' => is_null($latestStatus) ? null : [
                'title' => $latestStatus->title,
                'status' => $latestStatus->status,
                'description' => StringHelper::parse($latestStatus->description),
                'written_at' => DateHelper::formatDate($latestStatus->created_at, $employee->timezone),
                'author' => $author ? [
                    'id' => $author->id,
                    'name' => $author->name,
                    'avatar' => ImageHelper::getAvatar($author, 32),
                    'position' => (! $author->position) ? null : [
                        'id' => $author->position->id,
                        'title' => $author->position->title,
                    ],
                    'url' => route('employees.show', [
                        'company' => $company,
                        'employee' => $latestStatus->author,
                    ]),
                ] : null,
            ],
            'url_edit' => route('projects.edit', [
                'company' => $company,
                'project' => $project,
            ]),
            'url_delete' => route('projects.delete', [
                'company' => $company,
                'project' => $project,
            ]),
            'project_lead' => $lead ? [
                'id' => $lead->id,
                'name' => $lead->name,
                'avatar' => ImageHelper::getAvatar($lead, 35),
                'position' => (! $lead->position) ? null : [
                    'id' => $lead->position->id,
                    'title' => $lead->position->title,
                ],
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $lead,
                ]),
            ] : null,
            'links' => $linkCollection,
        ];
    }

    /**
     * Array containing the information needed to update the project details.
     *
     * @param Project $project
     * @return array
     */
    public static function edit(Project $project): array
    {
        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->code,
            'short_code' => $project->short_code,
            'summary' => $project->summary,
        ];
    }

    /**
     * Array containing all the permissions a user can do on the different
     * pages of the project, depending on his role.
     *
     * @param Project $project
     * @param Employee $employee
     * @return array
     */
    public static function permissions(Project $project, Employee $employee): array
    {
        $isInProject = $employee->isInProject($project->id);
        $isProjectLead = is_null($project->lead) ? false : $project->lead->id == $employee->id;

        $canEditLastUpdate = false;
        if ($isProjectLead || $employee->permission_level <= 200) {
            $canEditLastUpdate = true;
        }

        $canManageLinks = false;
        if ($isInProject || $employee->permission_level <= 200) {
            $canManageLinks = true;
        }

        return [
            'can_edit_latest_update' => $canEditLastUpdate,
            'can_manage_links' => $canManageLinks,
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
        $company = $project->company;

        // get random members of the project
        if ($project->employees->count() > 4) {
            $random = 4;
        } else {
            $random = $project->employees->count();
        }
        $randomMembers = $project->employees->random($random);

        $membersCollection = collect([]);
        foreach ($randomMembers as $member) {
            $membersCollection->push([
                'id' => $member->id,
                'avatar' => ImageHelper::getAvatar($member, 32),
                'name' => $member->name,
                'url' => route('employees.show', [
                    'company' => $company,
                    'employee' => $member,
                ]),
            ]);
        }

        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->code,
            'summary' => $project->summary,
            'members' => $membersCollection,
            'other_members_counter' => $project->employees->count() - 4,
        ];
    }

    /**
     * Search all employees matching a given criteria.
     *
     * @param Company $company
     * @param string|null $criteria
     * @return Collection
     */
    public static function searchProjectLead(Company $company, ?string $criteria): Collection
    {
        return $company->employees()
            ->select('id', 'first_name', 'last_name', 'avatar_file_id', 'email')
            ->notLocked()
            ->where(function ($query) use ($criteria) {
                $query->where('first_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('last_name', 'LIKE', '%'.$criteria.'%')
                    ->orWhere('email', 'LIKE', '%'.$criteria.'%');
            })
            ->orderBy('last_name', 'asc')
            ->take(10)
            ->get()
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'avatar' => ImageHelper::getAvatar($employee, 23),
                ];
            });
    }
}
