<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Models\Company\Team;
use Illuminate\Support\Collection;

class AdminTeamViewHelper
{
    /**
     * Array containing information about the teams.
     *
     * @param Collection $teams
     *
     * @return Collection
     */
    public static function teams(Collection $teams): Collection
    {
        $teamsCollection = collect([]);
        foreach ($teams as $team) {
            $teamsCollection->push([
                'id' => $team->id,
                'name' => $team->name,
                'url' => route('team.show', [
                    'company' => $team->company,
                    'team' => $team,
                ]),
            ]);
        }

        return $teamsCollection;
    }

    /**
     * Array containing information about a specific team.
     *
     * @param Team $teams
     *
     * @return array
     */
    public static function team(Team $team): array
    {
        return [
            'id' => $team->id,
            'name' => $team->name,
            'url' => route('team.show', [
                'company' => $team->company,
                'team' => $team,
            ]),
        ];
    }
}
