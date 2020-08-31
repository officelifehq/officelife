<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\Ship;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\User\Pronoun;
use App\Models\Company\Place;
use App\Models\Company\Skill;
use App\Models\Company\Answer;
use App\Models\Company\Morale;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use App\Models\Company\Position;
use App\Models\Company\TeamNews;
use App\Models\Company\CompanyNews;
use App\Models\Company\EmployeeLog;
use App\Models\Company\DirectReport;
use App\Models\Company\Notification;
use App\Models\Company\WorkFromHome;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\CompanyPTOPolicy;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use App\Models\Company\EmployeePlannedHoliday;
use App\Models\Company\EmployeeDailyCalendarEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_user(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertTrue($dwight->user()->exists());
    }

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertTrue($dwight->company()->exists());
    }

    /** @test */
    public function it_has_many_teams(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);
        $salesB = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        $dwight->teams()->sync([$sales->id]);
        $dwight->teams()->sync([$salesB->id]);

        $this->assertTrue($dwight->teams()->exists());
    }

    /** @test */
    public function it_has_many_direct_reports(): void
    {
        $manager = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'manager_id' => $manager->id,
        ]);

        $this->assertTrue($manager->directReports()->exists());
    }

    /** @test */
    public function it_has_many_managers(): void
    {
        $dwight = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->managers()->exists());
    }

    /** @test */
    public function it_has_many_logs(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(EmployeeLog::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->employeeLogs()->exists());
    }

    /** @test */
    public function it_has_one_position(): void
    {
        $dwight = factory(Employee::class)->create();

        $this->assertTrue($dwight->position()->exists());
    }

    /** @test */
    public function it_has_many_tasks(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Task::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->tasks()->exists());
    }

    /** @test */
    public function it_has_many_worklogs(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Worklog::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->worklogs()->exists());
    }

    /** @test */
    public function it_has_one_status(): void
    {
        $dwight = factory(Employee::class)->create();

        $this->assertTrue($dwight->status()->exists());
    }

    /** @test */
    public function it_has_many_notifications(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Notification::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->notifications()->exists());
    }

    /** @test */
    public function it_has_many_company_news(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(CompanyNews::class, 2)->create([
            'author_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->news()->exists());
    }

    /** @test */
    public function it_has_many_morale(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Morale::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->morales()->exists());
    }

    /** @test */
    public function it_has_many_places(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Place::class, 2)->create([
            'placable_id' => $dwight->id,
            'placable_type' => 'App\Models\Company\Employee',
        ]);

        $this->assertTrue($dwight->places()->exists());
    }

    /** @test */
    public function it_has_many_daily_logs(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(EmployeeDailyCalendarEntry::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->dailyLogs()->exists());
    }

    /** @test */
    public function it_has_many_planned_holidays(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(EmployeePlannedHoliday::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->plannedHolidays()->exists());
    }

    /** @test */
    public function it_has_one_pronoun(): void
    {
        $dwight = factory(Employee::class)->create();

        $this->assertTrue($dwight->pronoun()->exists());
    }

    /** @test */
    public function it_has_many_team_news(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(TeamNews::class, 2)->create([
            'author_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->teamNews()->exists());
    }

    /** @test */
    public function it_has_many_work_from_homes(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(WorkFromHome::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->workFromHomes()->exists());
    }

    /** @test */
    public function it_has_many_answers(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Answer::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->answers()->exists());
    }

    /** @test */
    public function it_has_many_hardware(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Hardware::class, 2)->create([
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->hardware()->exists());
    }

    /** @test */
    public function it_has_many_ships(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $sales = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);
        $featureA = factory(Ship::class)->create([
            'team_id' => $sales->id,
        ]);

        $dwight->ships()->sync([$featureA->id]);

        $this->assertTrue($dwight->ships()->exists());
    }

    /** @test */
    public function it_has_many_skills(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $skill = factory(Skill::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        $dwight->skills()->sync([$skill->id]);

        $this->assertTrue($dwight->skills()->exists());
    }

    /** @test */
    public function it_has_many_expenses(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Expense::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->expenses()->exists());
    }

    /** @test */
    public function it_has_approved_many_expenses(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Expense::class, 2)->create([
            'manager_approver_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->approvedExpenses()->exists());
    }

    /** @test */
    public function it_has_approved_many_expenses_as_the_accounting_department(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Expense::class, 2)->create([
            'accounting_approver_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->approvedAccountingExpenses()->exists());
    }

    /** @test */
    public function it_has_many_answers_to_rate_your_manager_surveys(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(RateYourManagerAnswer::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->rateYourManagerAnswers()->exists());
    }

    /** @test */
    public function it_has_many_surveys_about_being_a_manager(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(RateYourManagerSurvey::class, 2)->create([
            'manager_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->rateYourManagerSurveys()->exists());
    }

    /** @test */
    public function it_has_many_one_on_one_entries(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(OneOnOneEntry::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->oneOnOneEntriesAsEmployee()->exists());
    }

    /** @test */
    public function it_has_many_one_on_one_entries_as_manager(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(OneOnOneEntry::class, 2)->create([
            'manager_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->oneOnOneEntriesAsManager()->exists());
    }

    /** @test */
    public function it_scopes_the_employees_by_the_locked_status(): void
    {
        $dwight = factory(Employee::class)->create([
            'locked' => true,
        ]);
        $company = $dwight->company;
        factory(Employee::class, 3)->create([
            'company_id' => $company->id,
            'locked' => false,
        ]);

        $this->assertEquals(
            3,
            $company->employees()->notLocked()->get()->count()
        );
    }

    /** @test */
    public function it_returns_the_email_attribute(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertEquals(
            'dwigth@dundermifflin.com',
            $dwight->email
        );
    }

    /** @test */
    public function it_returns_the_name_attribute(): void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertEquals(
            'Dwight Schrute',
            $dwight->name
        );
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $dunder = factory(Company::class)->create([]);
        $dwight = factory(User::class)->create([]);
        $position = factory(Position::class)->create([
            'company_id' => $dunder->id,
            'title' => 'developer',
        ]);
        $pronoun = factory(Pronoun::class)->create([]);
        $michael = factory(Employee::class)->create([
            'company_id' => $dunder->id,
            'first_name' => 'michael',
            'last_name' => 'scott',
            'permission_level' => '100',
            'avatar' => 'avatar',
            'position_id' => $position->id,
            'description' => 'awesome employee',
            'pronoun_id' => $pronoun->id,
            'user_id' => $dwight->id,
            'birthdate' => '2010-01-01 00:00:00',
            'created_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $michael->id,
                'company' => [
                    'id' => $dunder->id,
                ],
                'name' => 'michael scott',
                'first_name' => 'michael',
                'last_name' => 'scott',
                'avatar' => 'avatar',
                'email' => 'dwigth@dundermifflin.com',
                'locked' => false,
                'birthdate' => [
                    'full' => 'Jan 01, 2010',
                    'partial' => 'January 1st',
                    'year' => 2010,
                    'month' => 1,
                    'day' => 1,
                    'age' => 8,
                ],
                'permission_level' => 'Administrator',
                'raw_description' => 'awesome employee',
                'parsed_description' => '<p>awesome employee</p>',
                'address' => null,
                'status' => [
                    'id' => $michael->status->id,
                    'name' => $michael->status->name,
                ],
                'position' => [
                    'id' => $position->id,
                    'title' => 'developer',
                ],
                'pronoun' => [
                    'id' => $pronoun->id,
                    'label' => $pronoun->label,
                ],
                'user' => [
                    'id' => $dwight->id,
                ],
                'created_at' => '2020-01-12 00:00:00',
            ],
            $michael->toObject()
        );
    }

    /** @test */
    public function it_returns_the_birthdate_attribute(): void
    {
        $randomDate = '1945-03-03';

        $dwight = factory(Employee::class)->create([
            'birthdate' => $randomDate,
        ]);

        $this->assertEquals(
            $randomDate,
            $dwight->birthdate->format('Y-m-d')
        );
    }

    /** @test */
    public function it_gets_the_list_of_the_employees_managers(): void
    {
        $dwight = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertEquals(
            3,
            $dwight->getListOfManagers()->count()
        );
    }

    /** @test */
    public function it_get_the_list_of_the_employees_direct_reports(): void
    {
        $dwight = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'company_id' => $dwight->company_id,
            'manager_id' => $dwight->id,
        ]);

        $this->assertEquals(
            3,
            $dwight->getListOfDirectReports()->count()
        );
    }

    /** @test */
    public function it_gets_the_path_for_the_invitation_link(): void
    {
        $dwight = factory(Employee::class)->create([
            'invitation_link' => 'dunder',
        ]);

        $this->assertEquals(
            config('app.url').'/invite/employee/dunder',
            $dwight->getPathInvitationLink()
        );
    }

    /** @test */
    public function it_checks_if_a_worklog_has_already_been_logged_today(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $dwight = factory(Employee::class)->create([]);
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => now(),
        ]);

        $this->assertTrue($dwight->hasAlreadyLoggedWorklogToday());

        $dwight = factory(Employee::class)->create([]);
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => Carbon::yesterday(),
        ]);
        $this->assertFalse($dwight->hasAlreadyLoggedWorklogToday());
    }

    /** @test */
    public function it_checks_if_a_morale_has_already_been_logged_today(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $dwight = factory(Employee::class)->create([]);
        factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => now(),
        ]);

        $this->assertTrue($dwight->hasAlreadyLoggedMoraleToday());

        $dwight = factory(Employee::class)->create([]);
        factory(Morale::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => Carbon::yesterday(),
        ]);
        $this->assertFalse($dwight->hasAlreadyLoggedMoraleToday());
    }

    /** @test */
    public function it_returns_the_current_address(): void
    {
        $dwight = factory(Employee::class)->create();
        factory(Place::class, 2)->create([
            'placable_id' => $dwight->id,
            'placable_type' => 'App\Models\Company\Employee',
        ]);

        $this->assertNull($dwight->getCurrentAddress());

        $place = factory(Place::class)->create([
            'placable_id' => $dwight->id,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ]);

        $address = $dwight->getCurrentAddress();
        $this->assertInstanceOf(
            Place::class,
            $address
        );

        $this->assertEquals(
            $place->id,
            $address->id
        );
    }

    /** @test */
    public function it_returns_the_holidays_information(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $dwight = factory(Employee::class)->create();
        factory(CompanyPTOPolicy::class)->create([
            'company_id' => $dwight->company_id,
            'year' => 2018,
        ]);

        $this->assertTrue(
            array_key_exists('amount_of_allowed_holidays', $dwight->getHolidaysInformation())
        );

        $this->assertTrue(
            array_key_exists('current_balance_round', $dwight->getHolidaysInformation())
        );

        $this->assertTrue(
            array_key_exists('number_holidays_left_to_earn_this_year', $dwight->getHolidaysInformation())
        );

        $this->assertTrue(
            array_key_exists('holidays_earned_each_month', $dwight->getHolidaysInformation())
        );
    }

    /** @test */
    public function it_checks_if_the_employee_is_in_a_given_team(): void
    {
        $dwight = factory(Employee::class)->create();
        $sales = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        $sales->employees()->attach(
            $dwight->id,
            [
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $this->assertTrue($dwight->isInTeam($sales->id));

        $dwight = factory(Employee::class)->create();
        $sales = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        $this->assertFalse($dwight->isInTeam($sales->id));
    }

    /** @test */
    public function it_checks_if_the_employee_is_the_manager_of_the_other_employee(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        factory(DirectReport::class)->create([
            'company_id' => $michael->company_id,
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($michael->isManagerOf($dwight->id));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $this->assertFalse($michael->isManagerOf($dwight->id));
    }
}
