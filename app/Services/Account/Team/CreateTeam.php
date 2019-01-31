<?php

namespace App\Services\Account\Team;

use App\Models\Account\Team;
use App\Services\BaseService;
use App\Services\Account\Account\LogAction;

class CreateTeam extends BaseService
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
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

        $author = $this->validatePermissions($data['author_id'], 'hr');

        $team = Team::create([
            'account_id' => $data['account_id'],
            'name' => $data['name'],
            'description' => $this->nullOrValue($data, 'description'),
            'is_dummy' => $this->valueOrFalse($data, 'is_dummy'),
        ]);

        (new LogAction)->execute([
            'account_id' => $data['account_id'],
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
