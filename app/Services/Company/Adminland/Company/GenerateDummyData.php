<?php

namespace App\Services\Company\Adminland\Company;

use Carbon\Carbon;
use App\Models\User\User;
use Faker\Factory as Faker;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Team;
use App\Services\BaseService;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Adminland\Team\CreateTeam;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;

class GenerateDummyData extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
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

        $this->createWorklogEntries();

        $this->createMoraleEntries();

        $this->createCompanyNewsEntries($data);

        $company->has_dummy_data = true;
        $company->save();

        NotifyEmployee::dispatch([
            'employee_id' => $data['author_id'],
            'action' => 'dummy_data_generated',
            'objects' => json_encode([
                'company_name' => $company->name,
            ]),
        ])->onQueue('low');

        // reset the cached object as it has changed
        $cachedCompanyObject = 'cachedCompanyObject_'.$data['author_id'];
        Cache::put($cachedCompanyObject, $company, now()->addMinutes(60));
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
            $this->addEmployee($data);
        }
    }

    /**
     * Create a user and add it to the company as an employee.
     *
     * @param array $data
     * @return Employee
     */
    private function addEmployee(array $data) : Employee
    {
        $faker = Faker::create();

        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'email' => $faker->safeEmail,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'permission_level' => config('homas.authorizations.user'),
            'send_invitation' => false,
            'is_dummy' => true,
        ];

        return (new AddEmployeeToCompany)->execute($request);
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
        $team = $this->createTeamWithEmployees($data, 'Sales', 18);

        // add current user to the team
        $currentEmployee = Employee::find($data['author_id']);
        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'employee_id' => $currentEmployee->id,
            'team_id' => $team->id,
            'is_dummy' => true,
        ];

        (new AddEmployeeToTeam)->execute($request);
    }

    /**
     * Create employees in a given team.
     *
     * @param array $data
     * @param string $teamName
     * @param int $numberOfEmployees
     * @return Team
     */
    private function createTeamWithEmployees(array $data, string $teamName, int $numberOfEmployees) : Team
    {
        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'name' => $teamName,
            'is_dummy' => true,
        ];

        $team = (new CreateTeam)->execute($request);
        $team->is_dummy = true;
        $team->save();

        for ($i = 1; $i <= $numberOfEmployees; $i++) {
            $employee = $this->addEmployee($data);

            $request = [
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'employee_id' => $employee->id,
                'team_id' => $team->id,
                'is_dummy' => true,
            ];

            (new AddEmployeeToTeam)->execute($request);
        }

        return $team;
    }

    /**
     * Create fake worklog entries for all employees of the first team in the
     * account.
     * This method does not use the dedicated service to add a worklog to an
     * employee because the service doesn't let people change the created_at
     * date (on purpose). Hence the only option is to record worklogs in the
     * database directly.
     *
     * @return void
     */
    private function createWorklogEntries()
    {
        $faker = Faker::create();

        $employees = Employee::whereHas('teams', function ($query) {
            $query->where('name', 'Sales');
        })->where('is_dummy', true)->get();

        foreach ($employees as $employee) {
            $date = Carbon::now()->subMonths(3);

            while (! $date->isSameDay(Carbon::now())) {
                if (rand(1, 10) >= 8) {
                    DB::table('worklog')->insert([
                        'employee_id' => $employee->id,
                        'content' => $faker->realText(50),
                        'is_dummy' => true,
                        'created_at' => $date,
                    ]);

                    $employee->consecutive_worklog_missed = 0;
                } else {
                    $employee->consecutive_worklog_missed = $employee->consecutive_worklog_missed + 1;
                }

                $employee->save();
                $date->addDay();
            }
        }
    }

    /**
     * Create fake morale entries for all employees.
     * This method does not use the dedicated service to log a morale to an
     * employee because the service doesn't let people change the created_at
     * date (on purpose). Hence the only option is to record morales in the
     * database directly.
     *
     * @return void
     */
    private function createMoraleEntries()
    {
        $faker = Faker::create();

        $employees = Employee::where('is_dummy', true)->get();

        foreach ($employees as $employee) {
            $date = Carbon::now()->subMonths(3);

            while (! $date->isSameDay(Carbon::now())) {
                if (rand(1, 10) >= 8) {
                    DB::table('morale')->insert([
                        'employee_id' => $employee->id,
                        'emotion' => rand(1, 3),
                        'comment' => $faker->realText(50),
                        'is_dummy' => true,
                        'created_at' => $date,
                    ]);
                }
                $date->addDay();
            }
        }
    }

    /**
     * Create fake company entries for all employees.
     *
     * @param array $data
     * @return void
     */
    private function createCompanyNewsEntries(array $data)
    {
        $faker = Faker::create();
        $numberOfCompanyNews = 20;

        for ($i = 1; $i <= $numberOfCompanyNews; $i++) {
            $request = [
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'title' => $faker->realText(75),
                'content' => $faker->realText(1500),
                'created_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'is_dummy' => true,
            ];

            (new CreateCompanyNews)->execute($request);
        }
    }
}
