<?php

namespace App\Services\Account\Account;

use App\Models\User\User;
use Faker\Factory as Faker;
use App\Services\BaseService;
use App\Models\Account\Account;
use App\Services\User\CreateUser;
use App\Services\Account\Team\CreateTeam;
use App\Services\Account\Team\AddUserToTeam;

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

        $account = Account::find($data['account_id']);

        $this->createFiveUsersWithoutTeam($data);

        $this->createThreeTeamsWithUsers($data);

        $account->has_dummy_data = true;
        $account->save();
    }

    /**
     * Create five users without a team.
     *
     * @param array $data
     * @return void
     */
    private function createFiveUsersWithoutTeam(array $data)
    {
        for ($i = 1; $i <= 5; $i++) {
            $this->createUser($data);
        }
    }

    /**
     * Create a user.
     *
     * @param array $data
     * @return User
     */
    private function createUser(array $data) : User
    {
        $faker = Faker::create();

        $request = [
            'account_id' => $data['account_id'],
            'author_id' => $data['author_id'],
            'email' => $faker->safeEmail,
            'password' => $faker->password,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'is_administrator' => false,
            'is_dummy' => true,
        ];

        return (new CreateUser)->execute($request);
    }

    /**
     * Create 3 teams with a bunch of users inside.
     *
     * @param array $data
     * @return void
     */
    private function createThreeTeamsWithUsers(array $data)
    {
        $this->createTeamWitUser($data, 'Legal department', 3);
        $this->createTeamWitUser($data, 'Design Team', 6);
        $this->createTeamWitUser($data, 'Sales', 18);
    }

    /**
     * Create five users without a team.
     *
     * @param array $data
     * @param string $name
     * @param int $users
     * @return void
     */
    private function createTeamWitUser(array $data, String $name, int $users)
    {
        $faker = Faker::create();

        $request = [
            'account_id' => $data['account_id'],
            'author_id' => $data['author_id'],
            'name' => $name,
            'is_dummy' => true,
        ];

        $team = (new CreateTeam)->execute($request);

        for ($i = 1; $i <= $users; $i++) {
            $user = $this->createUser($data);

            $request = [
                'account_id' => $data['account_id'],
                'author_id' => $data['author_id'],
                'user_id' => $user->id,
                'team_id' => $team->id,
                'is_dummy' => true,
            ];

            (new AddUserToTeam)->execute($request);
        }
    }
}
