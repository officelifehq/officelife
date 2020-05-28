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
use App\Models\Company\Question;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Services\Company\Adminland\Team\CreateTeam;
use App\Services\Company\Employee\Answer\CreateAnswer;
use App\Services\Company\Team\TeamNews\CreateTeamNews;
use App\Services\Company\Adminland\Hardware\LendHardware;
use App\Services\Company\Employee\Birthdate\SetBirthdate;
use App\Services\Company\Employee\Team\AddEmployeeToTeam;
use App\Services\Company\Team\Links\CreateTeamUsefulLink;
use App\Services\Company\Adminland\Hardware\CreateHardware;
use App\Services\Company\Adminland\Question\CreateQuestion;
use App\Services\Company\Team\Description\SetTeamDescription;
use App\Services\Company\Adminland\CompanyNews\CreateCompanyNews;
use App\Services\Company\Adminland\Employee\AddEmployeeToCompany;
use App\Services\Company\Employee\WorkFromHome\UpdateWorkFromHomeInformation;

class GenerateDummyData extends BaseService
{
    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
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
     */
    public function execute(array $data): void
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $company = Company::find($data['company_id']);

        $this->createFiveUsersWithoutTeam($data);

        $this->createThreeTeamsWithEmployees($data);

        $this->createWorklogEntries();

        $this->createMoraleEntries();

        $this->createWorkFromHomeEntries();

        $this->createCompanyNewsEntries($data);

        $this->createQuestions($data);

        $this->createHardware($data);

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
     *
     * @return Employee
     */
    private function addEmployee(array $data): Employee
    {
        $faker = Faker::create();

        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'email' => $faker->safeEmail,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'permission_level' => config('officelife.permission_level.user'),
            'send_invitation' => false,
            'is_dummy' => true,
        ];

        $employee = (new AddEmployeeToCompany)->execute($request);

        $this->addBirthdate($employee, $data);

        return $employee;
    }

    /**
     * Set birthdate.
     *
     * @param Employee $employee
     * @param array $data
     */
    private function addBirthdate(Employee $employee, array $data): void
    {
        $faker = Faker::create();
        $date = Carbon::createFromFormat('Y-m-d', $faker->dateTimeThisCentury()->format('Y-m-d'));

        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'employee_id' => $employee->id,
            'year' => $date->year,
            'month' => $date->month,
            'day' => $date->day,
            'is_dummy' => true,
        ];

        (new SetBirthdate)->execute($request);
    }

    /**
     * Create 3 teams with a bunch of employees inside.
     *
     * @param array $data
     */
    private function createThreeTeamsWithEmployees(array $data)
    {
        $this->createTeamWithEmployees($data, 'Legal department', 3);
        $this->createTeamWithEmployees($data, 'Design Team', 6);
        $team = $this->createTeamWithEmployees($data, 'Sales', 18);

        // add team news
        $this->createTeamNewsEntry($data, $team);

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
     * @param array  $data
     * @param string $teamName
     * @param int $numberOfEmployees
     *
     * @return Team
     */
    private function createTeamWithEmployees(array $data, string $teamName, int $numberOfEmployees): Team
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

        // add description
        if (rand(1, 3) == 1) {
            $request = [
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'team_id' => $team->id,
                'description' => 'This is probably the best team you have ever known. Welcome, passenger!',
            ];

            (new SetTeamDescription)->execute($request);
        }

        // add useful links
        if (rand(1, 3) == 1) {
            for ($i = 1; $i <= 3; $i++) {
                $request = [
                    'company_id' => $data['company_id'],
                    'author_id' => $data['author_id'],
                    'team_id' => $team->id,
                    'type' => 'slack',
                    'label' => '#dunder-mifflin',
                    'url' => 'https://slack.com',
                ];

                (new CreateTeamUsefulLink)->execute($request);
            }
        }

        // add employees
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
                    DB::table('worklogs')->insert([
                        'employee_id' => $employee->id,
                        'content' => $faker->realText(50),
                        'is_dummy' => true,
                        'created_at' => $date->format('Y-m-d H:i:s'),
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
                        'created_at' => $date->format('Y-m-d H:i:s'),
                    ]);
                }
                $date->addDay();
            }
        }
    }

    /**
     * Create fake Work from Home entries for all employees.
     */
    private function createWorkFromHomeEntries()
    {
        $employees = Employee::where('is_dummy', true)->get();

        foreach ($employees as $employee) {
            $date = Carbon::now()->subMonths(3);

            while (! $date->isSameDay(Carbon::now())) {
                if (rand(1, 10) >= 5) {
                    $request = [
                        'author_id' => $employee->id,
                        'employee_id' => $employee->id,
                        'company_id' => $employee->company_id,
                        'date' => $date->copy()->format('Y-m-d'),
                        'work_from_home' => true,
                    ];

                    (new UpdateWorkFromHomeInformation)->execute($request);
                }

                $date->addDay();
            }
        }
    }

    /**
     * Create fake company entries for all employees.
     *
     * @param array $data
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

    /**
     * Create fake questions.
     *
     * @param array $data
     */
    private function createQuestions(array $data)
    {
        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'title' => 'Which movies do you like the most?',
            'active' => false,
            'is_dummy' => true,
        ];

        $question = (new CreateQuestion)->execute($request);
        $this->createAnswers($data, $question);

        $request = [
            'company_id' => $data['company_id'],
            'author_id' => $data['author_id'],
            'title' => 'What do you like in life?',
            'active' => true,
            'is_dummy' => true,
        ];

        $question = (new CreateQuestion)->execute($request);
        $this->createAnswers($data, $question);
    }

    /**
     * Create fake answers for all employees.
     *
     * @param array $data
     * @param Question $question
     */
    private function createAnswers(array $data, Question $question)
    {
        $faker = Faker::create();
        $employees = Employee::where('is_dummy', true)->get();

        foreach ($employees as $employee) {
            if (rand(1, 3) >= 2) {
                $request = [
                    'company_id' => $data['company_id'],
                    'author_id' => $data['author_id'],
                    'employee_id' => $employee->id,
                    'question_id' => $question->id,
                    'body' => $faker->realText(1500),
                    'is_dummy' => true,
                ];

                (new CreateAnswer)->execute($request);
            }
        }
    }

    /**
     * Create a bunch of team news for the given team.
     *
     * @param array $data
     * @param Team $team
     */
    private function createTeamNewsEntry(array $data, Team $team)
    {
        $faker = Faker::create();
        $numberOfNews = 20;

        for ($i = 1; $i <= $numberOfNews; $i++) {
            $request = [
                'company_id' => $data['company_id'],
                'author_id' => $data['author_id'],
                'team_id' => $team->id,
                'title' => $faker->realText(75),
                'content' => $faker->realText(1500),
                'created_at' => $faker->dateTimeThisYear()->format('Y-m-d H:i:s'),
                'is_dummy' => true,
            ];

            (new CreateTeamNews)->execute($request);
        }
    }

    /**
     * Create a bunch of hardware and lend them to random employees.
     *
     * @param array $data
     */
    private function createHardware(array $data): void
    {
        $employees = Employee::where('is_dummy', true)->get();

        foreach ($employees as $employee) {
            if (rand(1, 3) >= 2) {
                $request = [
                    'company_id' => $data['company_id'],
                    'author_id' => $data['author_id'],
                    'name' => 'iPad',
                    'serial_number' => '32KLEo3310dF1102',
                    'is_dummy' => true,
                ];

                $hardware = (new CreateHardware)->execute($request);

                $request = [
                    'company_id' => $data['company_id'],
                    'author_id' => $data['author_id'],
                    'employee_id' => $employee->id,
                    'hardware_id' => $hardware->id,
                    'is_dummy' => true,
                ];

                (new LendHardware)->execute($request);
            }
        }
    }
}
