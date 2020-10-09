<?php

namespace App\Http\ViewHelpers\Project;

use App\Helpers\StringHelper;
use App\Models\Company\Company;
use App\Models\Company\Project;

class ProjectViewHelper
{
    /**
     * Array containing the information all the projects in the company.
     *
     * @param Company
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
     * @param Project
     * @param Company
     * @return array
     */
    public static function summary(Project $project, Company $company): array
    {
        $lead = $project->lead;

        return [
            'id' => $project->id,
            'name' => $project->name,
            'code' => $project->code,
            'summary' => $project->summary,
            'status' => $project->status,
            'raw_description' => is_null($project->description) ? null : $project->description,
            'parsed_description' => is_null($project->description) ? null : StringHelper::parse($project->description),
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
        ];
    }

    /**
     * Array containing the information needed to update the project details.
     *
     * @param Project
     * @param Company
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
}
