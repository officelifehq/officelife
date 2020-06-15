<?php

namespace App\Http\ViewHelpers\Company\Adminland;

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
}
