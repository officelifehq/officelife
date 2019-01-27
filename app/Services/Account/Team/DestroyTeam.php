<?php

namespace App\Services\Account\Team;

use App\Models\Account\Team;
use App\Services\BaseService;
use App\Services\Account\Account\LogAction;

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
            'account_id' => 'required|integer|exists:accounts,id',
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

        $this->validatePermissions($data['author_id'], 'hr');

        $team = Team::where('account_id', $data['account_id'])
            ->findOrFail($data['team_id']);

        $team->delete();

        (new LogAction)->execute([
            'account_id' => $data['account_id'],
            'action' => 'team_destroyed',
            'objects' => json_encode('{"author": '.$data['author_id'].', "team": '.$team->name.'}'),
        ]);

        return true;
    }
}
