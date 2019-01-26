<?php

namespace App\Services\Account\Team;

use App\Models\Account\Team;
use App\Models\Account\Account;
use App\Models\User\User;
use App\Services\BaseService;
use App\Services\Account\Account\LogAction;

class AddUserToTeam extends BaseService
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
            'user_id' => 'required|integer|exists:users,id',
            'team_id' => 'required|integer|exists:teams,id',
        ];
    }

    /**
     * Add a user to a team.
     *
     * @param array $data
     * @return Team
     */
    public function execute(array $data) : Team
    {
        $this->validate($data);

        User::where('account_id', $data['account_id'])
            ->findOrFail($data['user_id']);

        $team = Team::where('account_id', $data['account_id'])
            ->findOrFail($data['team_id']);

        $team->users()->attach($data['user_id'], ['account_id' => $data['account_id']]);

        (new LogAction)->execute([
            'account_id' => $data['account_id'],
            'action' => 'user_added_to_team',
            'objects' => json_encode('{"author": '.$data['author_id'].', "team": '. $data['team_id'] .', "user": '.$data['user_id'].'}'),
        ]);

        return $team;
    }
}
