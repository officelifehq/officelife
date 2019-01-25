<?php

namespace App\Services\User;

use App\Models\User\User;
use App\Models\Account\Team;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;

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
            'name' => 'required|email|string',
            'description' => 'required|string|max:255',
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

        if (! $this->uniqueInAccount($data)) {
            throw new EmailAlreadyUsedException;
        }

        return User::create([
            'account_id' => $data['account_id'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'is_administrator' => $data['is_administrator'],
        ]);
    }
}
