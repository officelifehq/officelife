<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Ship;
use App\Models\Company\Team;
use App\Models\User\Pronoun;
use App\Models\Company\Skill;
use App\Models\Company\Answer;
use App\Models\Company\ECoffee;
use App\Models\Company\Expense;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use App\Models\Company\Position;
use App\Models\Company\Question;
use App\Models\Company\Software;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\ECoffeeMatch;
use App\Models\Company\WorkFromHome;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\EmployeeStatus;
use App\Models\Company\TimeTrackingEntry;
use App\Models\Company\EmployeePositionHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Manager\AssignManager;
use App\Http\ViewHelpers\Employee\EmployeeShowViewHelper;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class EmployeeShowViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_information_about_the_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight->birthdate = Carbon::create(1975, 12, 22);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        $permissions = [
            'can_see_complete_address' => true,
            'can_see_full_birthdate' => true,
        ];

        $array = EmployeeShowViewHelper::informationAboutEmployee($dwight, $permissions, $michael);

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('first_name', $array);
        $this->assertArrayHasKey('last_name', $array);
        $this->assertArrayHasKey('avatar', $array);
        $this->assertArrayHasKey('twitter_handle', $array);
        $this->assertArrayHasKey('slack_handle', $array);
        $this->assertArrayHasKey('hired_at', $array);
        $this->assertArrayHasKey('contract_renewed_at', $array);
        $this->assertArrayHasKey('contract_rate', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('phone', $array);
        $this->assertArrayHasKey('locked', $array);
        $this->assertArrayHasKey('birthdate', $array);
        $this->assertArrayHasKey('raw_description', $array);
        $this->assertArrayHasKey('parsed_description', $array);
        $this->assertArrayHasKey('address', $array);
        $this->assertArrayHasKey('position', $array);
        $this->assertArrayHasKey('pronoun', $array);
        $this->assertArrayHasKey('user', $array);
        $this->assertArrayHasKey('status', $array);
        $this->assertArrayHasKey('url', $array);
    }

    /** @test */
    public function it_calculates_the_age_of_the_employee(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 12, 21));
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $dwight->update([
            'birthdate' => Carbon::create(1975, 12, 22),
        ]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        $permissions = [
            'can_see_complete_address' => true,
            'can_see_full_birthdate' => true,
        ];

        $array = EmployeeShowViewHelper::informationAboutEmployee($dwight, $permissions, $michael);

        $this->assertEquals([
            'date' => 'Dec 22, 1975',
            'age' => 42,
        ], $array['birthdate']);

        // Test age calculation on the same year
        Carbon::setTestNow(Carbon::create(2018, 12, 31));
        $array = EmployeeShowViewHelper::informationAboutEmployee($dwight, $permissions, $michael);
        $this->assertEquals([
            'date' => 'Dec 22, 1975',
            'age' => 43,
        ], $array['birthdate']);

        // Test age calculation on the birthday
        Carbon::setTestNow(Carbon::create(2018, 12, 22));
        $array = EmployeeShowViewHelper::informationAboutEmployee($dwight, $permissions, $michael);
        $this->assertEquals([
            'date' => 'Dec 22, 1975',
            'age' => 43,
        ], $array['birthdate']);

        // Test age calculation on the birthday on another timezone
        $michael->update([
            'timezone' => 'America/Chicago',
        ]);

        $array = EmployeeShowViewHelper::informationAboutEmployee($dwight, $permissions, $michael);
        $this->assertEquals([
            'date' => 'Dec 22, 1975',
            'age' => 42,
        ], $array['birthdate']);
    }

    /** @test */
    public function it_gets_a_collection_of_managers(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $jim = $this->createAnotherEmployee($michael);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $request = [
            'company_id' => $dwight->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $jim->id,
        ];

        (new AssignManager)->execute($request);

        $collection = EmployeeShowViewHelper::managers($dwight);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 35),
                    'position' => [
                        'id' => $michael->position->id,
                        'title' => $michael->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim, 35),
                    'position' => [
                        'id' => $jim->position->id,
                        'title' => $jim->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_direct_reports(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $jim = $this->createAnotherEmployee($michael);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $jim->id,
            'manager_id' => $michael->id,
        ];

        (new AssignManager)->execute($request);

        $collection = EmployeeShowViewHelper::directReports($michael);

        $this->assertEquals(2, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'avatar' => ImageHelper::getAvatar($dwight, 35),
                    'position' => [
                        'id' => $dwight->position->id,
                        'title' => $dwight->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$dwight->company_id.'/employees/'.$dwight->id,
                ],
                1 => [
                    'id' => $jim->id,
                    'name' => $jim->name,
                    'avatar' => ImageHelper::getAvatar($jim, 35),
                    'position' => [
                        'id' => $jim->position->id,
                        'title' => $jim->position->title,
                    ],
                    'url' => env('APP_URL').'/'.$jim->company_id.'/employees/'.$jim->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_the_work_from_home_statistics(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 10, 10));

        $michael = $this->createAdministrator();
        WorkFromHome::factory()->create([
            'employee_id' => $michael->id,
            'date' => '2010-01-01',
            'work_from_home' => true,
        ]);
        WorkFromHome::factory()->create([
            'employee_id' => $michael->id,
            'date' => '2018-02-01',
            'work_from_home' => true,
        ]);
        WorkFromHome::factory()->create([
            'employee_id' => $michael->id,
            'date' => '2018-03-01',
            'work_from_home' => true,
        ]);
        WorkFromHome::factory()->create([
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
            'work_from_home' => true,
        ]);

        $array = EmployeeShowViewHelper::workFromHomeStats($michael);

        $this->assertEquals(3, count($array));

        $this->assertEquals(
            [
                'work_from_home_today' => true,
                'number_times_this_year' => 3,
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/work/workfromhome',
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_a_collection_of_questions_and_answers(): void
    {
        $michael = $this->createAdministrator();
        $question = Question::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        Answer::factory()->create([
            'question_id' => $question->id,
            'employee_id' => $michael->id,
            'body' => 'this is my answer',
        ]);

        $collection = EmployeeShowViewHelper::questions($michael);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $question->id,
                    'title' => $question->title,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/questions/'.$question->id,
                    'answer' => [
                        'body' => 'this is my answer',
                    ],
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_teams(): void
    {
        $michael = $this->createAdministrator();
        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);

        $collection = EmployeeShowViewHelper::teams($michael->company->teams, $michael->company);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $team->id,
                    'name' => $team->name,
                    'team_leader' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/teams/'.$team->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_hardware(): void
    {
        $michael = $this->createAdministrator();
        $hardware = Hardware::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'name' => 'iphone',
            'serial_number' => '123',
        ]);

        $collection = EmployeeShowViewHelper::hardware($michael, ['can_see_hardware' => true]);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $hardware->id,
                    'name' => 'iphone',
                    'serial_number' => '123',
                ],
            ],
            $collection->toArray()
        );

        $collection = EmployeeShowViewHelper::hardware($michael, ['can_see_hardware' => false]);
        $this->assertNull($collection);
    }

    /** @test */
    public function it_gets_a_collection_of_recent_ships(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $team = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $featureA = Ship::factory()->create([
            'team_id' => $team->id,
        ]);
        $featureA->employees()->attach([$michael->id]);

        $collection = EmployeeShowViewHelper::recentShips($michael);

        $this->assertEquals(1, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $featureA->id,
                    'title' => $featureA->title,
                    'description' => $featureA->description,
                    'employees' => null,
                    'url' => route('ships.show', [
                        'company' => $featureA->team->company,
                        'team' => $featureA->team,
                        'ship' => $featureA->id,
                    ]),
                ],
            ],
            $collection->toArray()
        );

        $collection = EmployeeShowViewHelper::recentShips($dwight);
        $this->assertEquals(0, $collection->count());
        $this->assertEquals(
            [],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_recent_skills(): void
    {
        $michael = $this->createAdministrator();

        $skill = Skill::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $skill->employees()->attach([$michael->id]);

        $collection = EmployeeShowViewHelper::skills($michael);

        $this->assertEquals(1, $collection->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/skills/'.$skill->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_recent_expenses(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();

        $expense = Expense::factory()->create([
            'employee_id' => $michael->id,
            'created_at' => '2019-01-01 01:00:00',
        ]);

        // this expense is more than 30 days, it shouldn't appear in the collection
        Expense::factory()->create([
            'employee_id' => $michael->id,
            'created_at' => '2010-01-01 01:00:00',
        ]);

        $array = EmployeeShowViewHelper::expenses($michael, ['can_see_expenses' => true], $michael);

        $this->assertEquals(1, $array['expenses']->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $expense->id,
                    'title' => $expense->title,
                    'amount' => '$1.00',
                    'status' => 'created',
                    'expensed_at' => 'Jan 01, 1999',
                    'converted_amount' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/administration/expenses/'.$expense->id,
                ],
            ],
            $array['expenses']->toArray()
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/administration/expenses',
            $array['url']
        );

        $this->assertTrue(
            $array['hasMorePastExpenses']
        );

        $this->assertEquals(
            1,
            $array['totalPastExpenses']
        );

        $array = EmployeeShowViewHelper::expenses($michael, ['can_see_expenses' => false], $michael);
        $this->assertNull($array);
    }

    /** @test */
    public function it_gets_a_collection_of_latest_one_on_one_entries(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();
        $dwight = $this->createDirectReport($michael);

        $entry2019 = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
            'created_at' => '2019-01-01 01:00:00',
        ]);
        $entry2018 = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
            'created_at' => '2018-01-01 01:00:00',
        ]);
        $entry2017 = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
            'created_at' => '2017-01-01 01:00:00',
        ]);
        $entry2016 = OneOnOneEntry::factory()->create([
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
            'created_at' => '2016-01-01 01:00:00',
        ]);

        $array = EmployeeShowViewHelper::oneOnOnes($dwight, ['can_see_one_on_one_with_manager' => true], $michael);

        $this->assertEquals(3, $array['entries']->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $entry2019->id,
                    'happened_at' => 'Mar 02, 2020',
                    'manager' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 18),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id.'/performance/oneonones/'.$entry2019->id,
                ],
                1 => [
                    'id' => $entry2018->id,
                    'happened_at' => 'Mar 02, 2020',
                    'manager' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 18),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id.'/performance/oneonones/'.$entry2018->id,
                ],
                2 => [
                    'id' => $entry2017->id,
                    'happened_at' => 'Mar 02, 2020',
                    'manager' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 18),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id.'/performance/oneonones/'.$entry2017->id,
                ],
            ],
            $array['entries']->toArray()
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id.'/performance/oneonones',
            $array['view_all_url']
        );

        $array = EmployeeShowViewHelper::oneOnOnes($dwight, ['can_see_one_on_one_with_manager' => false], $michael);
        $this->assertNull($array);
    }

    /** @test */
    public function it_gets_a_collection_of_employee_statuses(): void
    {
        $michael = $this->createAdministrator();

        $collection = EmployeeShowViewHelper::employeeStatuses($michael->company);

        $this->assertEquals(1, $collection->count());

        $status = EmployeeStatus::first();

        $this->assertEquals(
            [
                0 => [
                    'id' => $status->id,
                    'name' => $status->name,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_employee_timesheets(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $now = Carbon::now();

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $task = ProjectTask::factory()->create([
            'project_id' => $project->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
            'status' => Timesheet::OPEN,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheet->id,
            'employee_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'happened_at' => Carbon::now()->startOfWeek(),
            'duration' => 100,
        ]);
        $timesheetB = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
            'status' => Timesheet::APPROVED,
            'approver_id' => $michael->id,
            'approved_at' => $now,
        ]);
        TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheetB->id,
            'employee_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $task->id,
            'happened_at' => Carbon::now()->startOfWeek(),
            'duration' => 100,
        ]);

        $array = EmployeeShowViewHelper::timesheets($michael, ['can_see_timesheets' => false]);
        $this->assertNull($array);

        $array = EmployeeShowViewHelper::timesheets($michael, ['can_see_timesheets' => true]);
        $this->assertEquals(2, count($array));
        $this->assertEquals(1, $array['entries']->count());

        $this->assertEquals(
            [
                0 => [
                    'id' => $timesheetB->id,
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                    'duration' => '01 h 40',
                    'status' => Timesheet::APPROVED,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/administration/timesheets/'.$timesheetB->id,
                ],
            ],
            $array['entries']->toArray()
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/administration/timesheets',
            $array['view_all_url']
        );
    }

    /** @test */
    public function it_gets_a_collection_of_pronouns(): void
    {
        $firstPronoun = Pronoun::orderBy('id', 'asc')->first();
        $collection = EmployeeShowViewHelper::pronouns();

        $this->assertEquals(
            [
                'id' => $firstPronoun->id,
                'label' => $firstPronoun->label,
            ],
            $collection->toArray()[0]
        );
    }

    /** @test */
    public function it_gets_a_collection_of_positions(): void
    {
        $position = Position::factory()->create([]);
        $collection = EmployeeShowViewHelper::positions($position->company);

        $this->assertEquals(
            [
                'id' => $position->id,
                'title' => $position->title,
            ],
            $collection->toArray()[0]
        );
    }

    /** @test */
    public function it_gets_a_collection_of_three_current_and_past_e_coffee_sessions(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $company = $michael->company;
        $company->e_coffee_enabled = true;
        $company->save();
        $company->refresh();

        $eCoffee = ECoffee::factory()->create([
            'company_id' => $company->id,
        ]);
        ECoffeeMatch::factory()->count(3)->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
        ]);
        $match = ECoffeeMatch::factory()->create([
            'e_coffee_id' => $eCoffee->id,
            'employee_id' => $michael->id,
            'with_employee_id' => $dwight->id,
        ]);

        $array = EmployeeShowViewHelper::eCoffees($michael, $company);

        $this->assertEquals(
            3,
            $array['eCoffees']->count()
        );

        $this->assertEquals(
            [
                'id' => $match->id,
                'ecoffee' => [
                    'started_at' => 'Jan 01, 2018',
                    'ended_at' => 'Jan 07, 2018',
                ],
                'with_employee' => [
                    'id' => $dwight->id,
                    'name' => $dwight->name,
                    'first_name' => $dwight->first_name,
                    'avatar' => ImageHelper::getAvatar($dwight, 35),
                    'position' => $dwight->position ? $dwight->position->title : null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$dwight->id,
                ],
            ],
            $array['eCoffees']->toArray()[0]
        );

        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id.'/ecoffees',
            $array['view_all_url']
        );
    }

    /** @test */
    public function it_gets_the_information_of_who_has_been_hired_after_the_employee(): void
    {
        $michael = Employee::factory()->create([
            'hired_at' => '2020-01-01 00:00:00',
        ]);
        Employee::factory()->count(2)->create([
            'hired_at' => '2010-01-01 00:00:00',
            'company_id' => $michael->company_id,
        ]);
        Employee::factory()->count(5)->create([
            'hired_at' => '2030-01-01 00:00:00',
            'company_id' => $michael->company_id,
        ]);

        $this->assertEquals(
            63,
            EmployeeShowViewHelper::hiredAfterEmployee($michael, $michael->company)
        );
    }

    /** @test */
    public function it_gets_the_information_about_past_positions(): void
    {
        $michael = Employee::factory()->create();
        $position = Position::factory()->create();
        $positionHistoryA = EmployeePositionHistory::factory()->create([
            'started_at' => '2010-01-01 00:00:00',
            'ended_at' => null,
            'employee_id' => $michael->id,
            'position_id' => $position->id,
        ]);
        $positionHistoryB = EmployeePositionHistory::factory()->create([
            'started_at' => '2007-01-01 00:00:00',
            'ended_at' => '2009-01 00:00:00',
            'employee_id' => $michael->id,
            'position_id' => $position->id,
        ]);

        $this->assertEquals(
            [
                0 => [
                    'id' => $positionHistoryA->id,
                    'position' => $position->title,
                    'started_at' => 'Jan 2010',
                    'ended_at' => null,
                ],
                1 => [
                    'id' => $positionHistoryB->id,
                    'position' => $position->title,
                    'started_at' => 'Jan 2007',
                    'ended_at' => 'Jan 2009',
                ],
            ],
            EmployeeShowViewHelper::employeeCurrentAndPastPositions($michael, $michael->company)->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_software(): void
    {
        $michael = $this->createAdministrator();
        $software = Software::factory()->create([
            'company_id' => $michael->company_id,
            'name' => 'iphone',
        ]);
        $software->employees()->syncWithoutDetaching([$michael->id]);

        $collection = EmployeeShowViewHelper::softwares($michael, ['can_see_software' => true]);

        $this->assertEquals(1, $collection->count());
        $this->assertEquals(
            [
                0 => [
                    'id' => $software->id,
                    'name' => 'iphone',
                ],
            ],
            $collection->toArray()
        );

        $collection = EmployeeShowViewHelper::softwares($michael, ['can_see_software' => false]);
        $this->assertNull($collection);
    }
}
