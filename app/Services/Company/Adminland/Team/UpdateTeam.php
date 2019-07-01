<?php

namespace App\Services\Company\Adminland\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Services\Company\Adminland\Company\LogAuditAction;

class UpdateTeam extends BaseService
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
            'name' => 'required|string|max:255',
            'is_dummy' => 'nullable|boolean',
        ];
    }

    /**
     * Update a team.
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

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $team->update([
            'name' => $data['name'],
        ]);

        (new LogAuditAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'team_updated',
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
