<?php

namespace App\Services\Account\Team;

use App\Models\Account\Team;
use App\Services\BaseService;

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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:65535',
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

        return Team::create([
            'account_id' => $data['account_id'],
            'name' => $data['name'],
            'description' => $this->nullOrValue($data, 'description'),
        ]);
    }
}
