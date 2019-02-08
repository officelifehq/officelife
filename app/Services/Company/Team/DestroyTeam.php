<?php

namespace App\Services\Company\Team;

use App\Models\Company\Team;
use App\Services\BaseService;
use App\Services\Company\Company\LogAction;

class DestroyTeam extends BaseService
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
        ];
    }

    /**
     * Update a team.
     *
     * @param array $data
     * @return bool
     */
    public function execute(array $data) : bool
    {
        $this->validate($data);

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.hr')
        );

        $team = Team::where('company_id', $data['company_id'])
            ->findOrFail($data['team_id']);

        $team->delete();

        (new LogAction)->execute([
            'company_id' => $data['company_id'],
            'action' => 'team_destroyed',
            'objects' => json_encode([
                'author_id' => $author->id,
                'author_name' => $author->name,
                'team_name' => $team->name,
            ]),
        ]);

        return true;
    }
}
