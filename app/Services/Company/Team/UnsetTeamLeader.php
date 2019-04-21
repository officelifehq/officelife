<?php

namespace App\Services\Company\Team;

use App\Models\Company\Team;
use App\Services\BaseService;

class UnsetTeamLeader extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
            'team_id' => 'required|integer|exists:teams,id',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Remove the team's leader.
     *
     * @param array $data
     * @return Team
     */
    public function execute(array $data): Team
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $oldTeamLeader = $team->leader;

        $team->team_leader_id = null;
        $team->save();

        (new LogTeamAction)->execute([
            'company_id' => $data['company_id'],
            'team_id' => $data['team_id'],
            'action' => 'team_leader_removed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'team_leader_id' => $oldTeamLeader->id,
                'team_leader_name' => $oldTeamLeader->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $team;
    }
}
