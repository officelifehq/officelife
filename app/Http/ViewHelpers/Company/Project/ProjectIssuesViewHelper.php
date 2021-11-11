<?php

namespace App\Http\ViewHelpers\Company\Project;

use App\Helpers\DateHelper;
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
            'created_at' => DateHelper::formatMonthAndDay($issue->created_at),
            'story_points' => $issue->story_points,
            'type' => $issue->type ? [
                'name' => $issue->type->name,
                'icon_hex_color' => $issue->type->icon_hex_color,
            ] : null,
            'url' => [
            ],
        ];
    }
}
