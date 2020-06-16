<?php

namespace App\Http\ViewHelpers\Team;

use App\Models\Company\Team;
use App\Helpers\StringHelper;

class TeamViewHelper
{
    /**
     * Array containing all the basic information about the given team.
     *
     * @param Team $team
     *
     * @return array
     */
    public static function team(Team $team): array
    {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'raw_description' => is_null($team->description) ? null : $team->description,
            'parsed_description' => is_null($team->description) ? null : StringHelper::parse($team->description),
            'team_leader' => is_null($team->leader) ? null : [
                'id' => $team->leader->id,
                'name' => $team->leader->name,
                'avatar' => $team->leader->avatar,
                'position' => (! $team->leader->position) ? null : [
                    'title' => $team->leader->position->title,
                ],
            ],
        ];
    }
}
