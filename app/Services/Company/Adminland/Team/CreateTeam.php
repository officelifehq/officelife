<?php

namespace App\Services\Company\Adminland\Team;

use App\Jobs\LogTeamAudit;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;

class CreateTeam extends BaseService
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
            'author_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Create a team.
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
            config('homas.authorizations.hr')
        );

        $team = Team::create([
            'company_id' => $data['company_id'],
            'name' => $data['name'],
        ]);

        LogAccountAudit::dispatch([
            'company_id' => $data['company_id'],
            'action' => 'team_created',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        LogTeamAudit::dispatch([
            'company_id' => $data['company_id'],
            'team_id' => $team->id,
            'action' => 'team_created',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'team_id' => $team->id,
                'team_name' => $team->name,
            ]),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        return $team;
    }
}
