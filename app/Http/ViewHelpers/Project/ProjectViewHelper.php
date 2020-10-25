<?php

namespace App\Http\ViewHelpers\Project;

use App\Helpers\DateHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;

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
        $projects = $company->projects()->orderBy('name')->get();

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
     * @return array
     */
    public static function summary(Project $project, Company $company): array
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
                'written_at' => DateHelper::formatDate($latestStatus->created_at),
                'author' => $latestStatus->author ? [
                    'id' => $latestStatus->author->id,
                    'name' => $latestStatus->author->name,
                    'avatar' => $latestStatus->author->avatar,
                    'position' => (! $latestStatus->author->position) ? null : [
                        'id' => $latestStatus->author->position->id,
                        'title' => $latestStatus->author->position->title,
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
                'avatar' => $lead->avatar,
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
        $lead = $project->lead;

        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->code,
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
}
