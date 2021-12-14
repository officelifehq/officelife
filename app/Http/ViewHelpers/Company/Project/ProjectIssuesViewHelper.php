<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Project;
use Illuminate\Support\Collection;
use App\Models\Company\ProjectIssue;

class ProjectIssuesViewHelper
{
    /**
     * All the data about the given issue.
     *
     * @param ProjectIssue $issue
     * @return array
     */
    public static function issueData(ProjectIssue $issue): array
    {
        $assigneesCollection = collect();
        foreach ($issue->assignees as $assignee) {
            $assigneesCollection->push([
                'id' => $assignee->id,
                'name' => $assignee->name,
                'avatar' => ImageHelper::getAvatar($assignee, 25),
                'url' => [
                    'show' => route('employees.show', [
                        'company' => $assignee->company_id,
                        'employee' => $assignee,
                    ]),
                    'destroy' => route('projects.issues.assignees.destroy', [
                        'company' => $assignee->company_id,
                        'project' => $issue->project->id,
                        'board' => $issue->board->id,
                        'issue' => $issue->id,
                        'assignee' => $assignee->id,
                    ]),
                ],
            ]);
        }

        return [
            'id' => $issue->id,
            'key' => $issue->key,
            'title' => $issue->title,
            'slug' => $issue->slug,
            'description' => $issue->description,
            'parsed_description' => $issue->description ? StringHelper::parse($issue->description) : null,
            'created_at' => DateHelper::formatDate($issue->created_at),
            'story_points' => [
                'points' => $issue->story_points,
                'url' => [
                    'store' => route('projects.issues.points.store', [
                        'company' => $issue->project->company_id,
                        'project' => $issue->project->id,
                        'board' => $issue->board->id,
                        'issue' => $issue->id,
                    ]),
                ],
            ],
            'type' => $issue->type ? [
                'name' => $issue->type->name,
                'icon_hex_color' => $issue->type->icon_hex_color,
            ] : null,
            'author' => $issue->reporter ? [
                'id' => $issue->reporter->id,
                'name' => $issue->reporter->name,
                'avatar' => ImageHelper::getAvatar($issue->reporter, 25),
                'url' => route('employees.show', [
                    'company' => $issue->reporter->company_id,
                    'employee' => $issue->reporter,
                ]),
            ] : null,
            'project' => [
                'id' => $issue->project->id,
                'name' => $issue->project->name,
                'url' => route('projects.boards.index', [
                    'company' => $issue->project->company_id,
                    'project' => $issue->project->id,
                ]),
            ],
            'assignees' => [
                'data' => $assigneesCollection,
                'url' => [
                    'index' => route('projects.members', [
                        'company' => $issue->project->company_id,
                        'project' => $issue->project->id,
                        'board' => $issue->board->id,
                    ]),
                    'store' => route('projects.issues.assignees.store', [
                        'company' => $issue->project->company_id,
                        'project' => $issue->project->id,
                        'board' => $issue->board->id,
                        'issue' => $issue->id,
                    ]),
                ],
            ],
            'url' => [
            ],
        ];
    }

    /**
     * Get all the members of the project.
     *
     * @param Project $project
     * @return Collection
     */
    public static function members(Project $project): Collection
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
                'avatar' => ImageHelper::getAvatar($member, 64),
            ]);
        }

        return $membersCollection;
    }
}
