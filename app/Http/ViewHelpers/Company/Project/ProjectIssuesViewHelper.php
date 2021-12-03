<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
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
        return [
            'id' => $issue->id,
            'key' => $issue->key,
            'title' => $issue->title,
            'slug' => $issue->slug,
            'created_at' => DateHelper::formatDate($issue->created_at),
            'story_points' => $issue->story_points,
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
            'url' => [
            ],
        ];
    }
}
