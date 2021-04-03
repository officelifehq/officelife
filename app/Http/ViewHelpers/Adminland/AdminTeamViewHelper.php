<?php

namespace App\Http\ViewHelpers\Adminland;

use App\Helpers\ImageHelper;
use App\Models\Company\Team;
use Illuminate\Support\Collection;

class AdminTeamViewHelper
{
    /**
     * Array containing information about the teams.
     *
     * @param Collection $teams
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
     * @param Team $team
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

    /**
     * Get all the team audit logs.
     *
     * @param mixed $logs
     * @return Collection
     */
    public static function logs($logs): Collection
    {
        $logsCollection = collect([]);
        foreach ($logs as $log) {
            $author = $log->author;

            $logsCollection->push([
                'id' => $log->id,
                'action' => $log->action,
                'objects' => json_decode($log->objects),
                'localized_content' => $log->content,
                'author' => [
                    'id' => is_null($author) ? null : $author->id,
                    'name' => is_null($author) ? $log->author_name : $author->name,
                    'avatar' => is_null($author) ? null : ImageHelper::getAvatar($author, 35),
                    'url' => is_null($author) ? null : route('employees.show', [
                        'company' => $author->company_id,
                        'employee' => $author,
                    ]),
                ],
                'localized_audited_at' => $log->date,
                'audited_at' => $log->audited_at,
            ]);
        }

        return $logsCollection;
    }
}
