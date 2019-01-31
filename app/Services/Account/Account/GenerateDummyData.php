<?php

namespace App\Services\Account\Account;

use Faker\Factory as Faker;
use App\Services\BaseService;
use App\Models\Account\Account;
use App\Services\User\CreateUser;

class GenerateDummyData extends BaseService
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

        $this->createFiveUsersWithoutTeam($data);
    }

    /**
     * Create five users without a team.
     *
     * @param array $data
     * @return void
     */
    private function createFiveUsersWithoutTeam(array $data)
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++) {
            $request = [
                'account_id' => $data['account_id'],
                'author_id' => $data['author_id'],
                'email' => $faker->safeEmail,
                'password' => $faker->password,
                'is_administrator' => false,
                'is_dummy' => true,
            ];

            (new CreateUser)->execute($request);
        }
    }
}
