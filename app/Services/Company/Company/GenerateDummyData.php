<?php

namespace App\Services\Company\Company;

use App\Models\User\User;
use Faker\Factory as Faker;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Services\User\CreateAccount;
use App\Services\Company\Team\CreateTeam;
use App\Services\Company\Team\AddEmployeeToTeam;

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

        $author = $this->validatePermissions(
            $data['author_id'],
            $data['company_id'],
            config('homas.authorizations.administrator')
        );

        $company = Company::find($data['company_id']);

        $this->createFiveUsersWithoutTeam($data);

        $this->createThreeTeamsWithEmployees($data);

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
     * Create a user and add it to the company as an employee.
     *
     * @param array $data
     * @return Employee
     */
    private function createAccount(array $data) : Employee
    {
        $faker = Faker::create();

        $request = [
            'email' => $faker->safeEmail,
            'password' => 'password',
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
        ];

        $user = (new CreateAccount)->execute($request);
        $user->is_dummy = true;
        $user->save();

        return (new AddUserToCompany)->execute([
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'user_id' => $user->id,
            'permission_level' => config('homas.authorizations.user'),
            'is_dummy' => true,
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
        $this->createTeamWithEmployees($data, 'Legal department', 3);
        $this->createTeamWithEmployees($data, 'Design Team', 6);
        $this->createTeamWithEmployees($data, 'Sales', 18);
    }

    /**
     * Create five employees without a team.
     *
     * @param array $data
     * @param string $name
     * @param int $employees
     * @return void
     */
    private function createTeamWithEmployees(array $data, String $name, int $employees)
    {
        $faker = Faker::create();

        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'name' => $name,
        ];

        $team = (new CreateTeam)->execute($request);
        $team->is_dummy = true;
        $team->save();

        for ($i = 1; $i <= $employees; $i++) {
            $employee = $this->createAccount($data);

            $request = [
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'employee_id' => $employee->id,
                'team_id' => $team->id,
                'is_dummy' => true,
            ];

            (new AddEmployeeToTeam)->execute($request);
        }
    }
}
