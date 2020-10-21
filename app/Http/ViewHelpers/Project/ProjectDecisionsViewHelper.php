<?php

namespace App\Http\ViewHelpers\Project;

use App\Models\Company\Project;

class ProjectDecisionsViewHelper
{
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
}
