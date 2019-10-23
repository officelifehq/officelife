<?php

namespace App\Services\Company\Team;

use Carbon\Carbon;
use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class UnsetTeamLeader extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
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
    public function execute(array $data) : Team
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('kakene.authorizations.hr')
        );

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $oldTeamLeader = $team->leader;

        $team->team_leader_id = null;
        $team->save();

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_leader_removed',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_id' => $oldTeamLeader->id,
                'team_leader_name' => $oldTeamLeader->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        LogTeamAudit::dispatch([
            'team_id' => $team->id,
            'action' => 'team_leader_removed',
            'author_id' => $author->id,
            'author_name' => $author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'team_leader_id' => $oldTeamLeader->id,
                'team_leader_name' => $oldTeamLeader->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ])->onQueue('low');

        return $team;
    }
}
