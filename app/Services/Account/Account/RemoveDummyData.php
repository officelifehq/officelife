<?php

namespace App\Services\Account\Account;

use App\Models\User\User;
use Faker\Factory as Faker;
use App\Services\BaseService;
use App\Models\Account\Account;

class RemoveDummyData extends BaseService
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
        ];
    }

    /**
     * Generate dummy data for the given account.
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data)
    {
        $this->validate($data);

        $account = Account::find($data['account_id']);

        $this->removeTeam($data);

        $this->removeUsers($data);

        $account->has_dummy_data = false;
        $account->save();
    }

    /**
     * Remove dummy team.
     *
     * @param array $data
     * @return void
     */
    private function removeTeam(array $data)
    {
        DB::table('teams')
            ->where('account_id', $data['account_id'])
            ->where('is_dummy', true)
            ->delete();
    }

    /**
     * Remove dummy users.
     *
     * @param array $data
     * @return void
     */
    private function removeUsers(array $data)
    {
        DB::table('users')
            ->where('account_id', $data['account_id'])
            ->where('is_dummy', true)
            ->delete();
    }
}
