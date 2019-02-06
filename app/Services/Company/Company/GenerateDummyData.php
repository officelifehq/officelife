<?php

namespace App\Services\Company\Company;

use App\Models\User\User;
use Faker\Factory as Faker;
use App\Services\BaseService;
use App\Services\User\CreateAccount;
use App\Services\Company\Team\CreateTeam;
use App\Services\Company\Team\AddEmployeeToTeam;
use App\Services\Company\Team\AddUserToCompany;

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
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Generate dummy data for the given company.
     *
     * @param array $data
     * @return void
     */
    public function execute(array $data)
    {
        $this->validate($data);

        $company = Company::find($data['company_id']);

        $this->createFiveUsersWithoutTeam($data);

        $this->createThreeTeamsWithUsers($data);

        $company->has_dummy_data = true;
        $company->save();
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
            $this->createAccount($data);
        }
    }

    /**
     * Create a user.
     *
     * @param array $data
     * @return User
     */
    private function createAccount(array $data) : User
    {
        $faker = Faker::create();

        $request = [
            'email' => $faker->safeEmail,
            'password' => $faker->password,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ];

        $user = (new CreateAccount)->execute($request);
        $user->is_dummy = true;
        $user->save();

        (new AddUserToCompany)->execute([
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'user_id' => $user->id,
            'permission_level' => config('homas.authorizations.user'),
        ]);
    }

    /**
     * Create 3 teams with a bunch of employees inside.
     *
     * @param array $data
     * @return void
     */
    private function createThreeTeamsWithEmployees(array $data)
    {
        $this->createTeamWithEmployee($data, 'Legal department', 3);
        $this->createTeamWithEmployee($data, 'Design Team', 6);
        $this->createTeamWithEmployee($data, 'Sales', 18);
    }

    /**
     * Create five employees without a team.
     *
     * @param array $data
     * @param string $name
     * @param int $employees
     * @return void
     */
    private function createTeamWithEmployee(array $data, String $name, int $employees)
    {
        $faker = Faker::create();

        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'name' => $name,
        ];

        $team = (new CreateTeam)->execute($request);

        for ($i = 1; $i <= $users; $i++) {
            $user = $this->createAccount($data);

            $request = [
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'user_id' => $user->id,
                'team_id' => $team->id,
            ];

            (new AddUserToTeam)->execute($request);
        }
    }
}
