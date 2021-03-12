<?php

namespace Tests\Unit\Models\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User\User;
use App\Models\Company\File;
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
use App\Models\Company\Project;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\Hardware;
use App\Models\Company\Position;
use App\Models\Company\TeamNews;
use App\Models\Company\Timesheet;
use App\Models\Company\CompanyNews;
use App\Models\Company\EmployeeLog;
use App\Models\Company\ProjectTask;
use App\Models\Company\DirectReport;
use App\Models\Company\Notification;
use App\Models\Company\WorkFromHome;
use App\Models\Company\OneOnOneEntry;
use App\Models\Company\ConsultantRate;
use App\Models\Company\ProjectDecision;
use App\Models\Company\CompanyPTOPolicy;
use App\Models\Company\GuessEmployeeGame;
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
        $dwight = Employee::factory()->create([]);
        $this->assertTrue($dwight->user()->exists());
    }

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $dwight = Employee::factory()->create([]);
        $this->assertTrue($dwight->company()->exists());
    }

    /** @test */
    public function it_has_many_teams(): void
    {
        $dwight = Employee::factory()->create([]);
        $sales = Team::factory()->create([
            'company_id' => $dwight->company_id,
        ]);
        $salesB = Team::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $dwight->teams()->sync([$sales->id]);
        $dwight->teams()->sync([$salesB->id]);

        $this->assertTrue($dwight->teams()->exists());
    }

    /** @test */
    public function it_has_many_direct_reports(): void
    {
        $manager = Employee::factory()->create([]);
        DirectReport::factory()->count(3)->create([
            'manager_id' => $manager->id,
        ]);

        $this->assertTrue($manager->directReports()->exists());
    }

    /** @test */
    public function it_has_many_managers(): void
    {
        $dwight = Employee::factory()->create([]);
        DirectReport::factory()->count(3)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->managers()->exists());
    }

    /** @test */
    public function it_has_many_logs(): void
    {
        $dwight = Employee::factory()->create();
        EmployeeLog::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->employeeLogs()->exists());
    }

    /** @test */
    public function it_has_one_position(): void
    {
        $dwight = Employee::factory()->create();

        $this->assertTrue($dwight->position()->exists());
    }

    /** @test */
    public function it_has_many_tasks(): void
    {
        $dwight = Employee::factory()->create();
        Task::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->tasks()->exists());
    }

    /** @test */
    public function it_has_many_worklogs(): void
    {
        $dwight = Employee::factory()->create();
        Worklog::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->worklogs()->exists());
    }

    /** @test */
    public function it_has_one_status(): void
    {
        $dwight = Employee::factory()->create();

        $this->assertTrue($dwight->status()->exists());
    }

    /** @test */
    public function it_has_many_notifications(): void
    {
        $dwight = Employee::factory()->create();
        Notification::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->notifications()->exists());
    }

    /** @test */
    public function it_has_many_company_news(): void
    {
        $dwight = Employee::factory()->create();
        CompanyNews::factory()->count(2)->create([
            'author_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->news()->exists());
    }

    /** @test */
    public function it_has_many_morale(): void
    {
        $dwight = Employee::factory()->create();
        Morale::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->morales()->exists());
    }

    /** @test */
    public function it_has_many_places(): void
    {
        $dwight = Employee::factory()->create();
        Place::factory()->count(2)->create([
            'placable_id' => $dwight->id,
            'placable_type' => 'App\Models\Company\Employee',
        ]);

        $this->assertTrue($dwight->places()->exists());
    }

    /** @test */
    public function it_has_many_daily_logs(): void
    {
        $dwight = Employee::factory()->create();
        EmployeeDailyCalendarEntry::factory(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->dailyLogs()->exists());
    }

    /** @test */
    public function it_has_many_planned_holidays(): void
    {
        $dwight = Employee::factory()->create();
        EmployeePlannedHoliday::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->plannedHolidays()->exists());
    }

    /** @test */
    public function it_has_one_pronoun(): void
    {
        $dwight = Employee::factory()->create();

        $this->assertTrue($dwight->pronoun()->exists());
    }

    /** @test */
    public function it_has_many_team_news(): void
    {
        $dwight = Employee::factory()->create();
        TeamNews::factory()->count(2)->create([
            'author_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->teamNews()->exists());
    }

    /** @test */
    public function it_has_many_work_from_homes(): void
    {
        $dwight = Employee::factory()->create();
        WorkFromHome::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->workFromHomes()->exists());
    }

    /** @test */
    public function it_has_many_answers(): void
    {
        $dwight = Employee::factory()->create();
        Answer::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->answers()->exists());
    }

    /** @test */
    public function it_has_many_hardware(): void
    {
        $dwight = Employee::factory()->create();
        Hardware::factory()->count(2)->create([
            'company_id' => $dwight->company_id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->hardware()->exists());
    }

    /** @test */
    public function it_has_many_ships(): void
    {
        $dwight = Employee::factory()->create([]);
        $sales = Team::factory()->create([
            'company_id' => $dwight->company_id,
        ]);
        $featureA = Ship::factory()->create([
            'team_id' => $sales->id,
        ]);

        $dwight->ships()->sync([$featureA->id]);

        $this->assertTrue($dwight->ships()->exists());
    }

    /** @test */
    public function it_has_many_skills(): void
    {
        $dwight = Employee::factory()->create([]);
        $skill = Skill::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $dwight->skills()->sync([$skill->id]);

        $this->assertTrue($dwight->skills()->exists());
    }

    /** @test */
    public function it_has_many_expenses(): void
    {
        $dwight = Employee::factory()->create();
        Expense::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->expenses()->exists());
    }

    /** @test */
    public function it_has_approved_many_expenses(): void
    {
        $dwight = Employee::factory()->create();
        Expense::factory()->count(2)->create([
            'manager_approver_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->approvedExpenses()->exists());
    }

    /** @test */
    public function it_has_approved_many_expenses_as_the_accounting_department(): void
    {
        $dwight = Employee::factory()->create();
        Expense::factory()->count(2)->create([
            'accounting_approver_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->approvedAccountingExpenses()->exists());
    }

    /** @test */
    public function it_has_many_answers_to_rate_your_manager_surveys(): void
    {
        $dwight = Employee::factory()->create();
        RateYourManagerAnswer::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->rateYourManagerAnswers()->exists());
    }

    /** @test */
    public function it_has_many_surveys_about_being_a_manager(): void
    {
        $dwight = Employee::factory()->create();
        RateYourManagerSurvey::factory()->count(2)->create([
            'manager_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->rateYourManagerSurveys()->exists());
    }

    /** @test */
    public function it_has_many_one_on_one_entries(): void
    {
        $dwight = Employee::factory()->create();
        OneOnOneEntry::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->oneOnOneEntriesAsEmployee()->exists());
    }

    /** @test */
    public function it_has_many_one_on_one_entries_as_manager(): void
    {
        $dwight = Employee::factory()->create();
        OneOnOneEntry::factory()->count(2)->create([
            'manager_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->oneOnOneEntriesAsManager()->exists());
    }

    /** @test */
    public function it_has_many_games_as_player(): void
    {
        $dwight = Employee::factory()->create();
        GuessEmployeeGame::factory()->count(2)->create([
            'employee_who_played_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->gamesAsPlayer()->exists());
    }

    /** @test */
    public function it_has_many_games_as_player_to_find(): void
    {
        $dwight = Employee::factory()->create();
        GuessEmployeeGame::factory()->count(2)->create([
            'employee_to_find_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->gamesAsPersonToFind()->exists());
    }

    /** @test */
    public function it_has_many_projects(): void
    {
        $dwight = Employee::factory()->create();
        $project = Project::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $dwight->projects()->sync([$project->id]);

        $this->assertTrue($dwight->projects()->exists());
    }

    /** @test */
    public function it_has_many_project_tasks_as_author(): void
    {
        $michael = Employee::factory()
            ->has(ProjectTask::factory()->count(2), 'projectTasksAsAuthor')
            ->create();

        $this->assertTrue($michael->projectTasksAsAuthor()->exists());
    }

    /** @test */
    public function it_has_many_project_tasks_as_assignee(): void
    {
        $michael = Employee::factory()
            ->has(ProjectTask::factory()->count(2), 'assigneeOfprojectTasks')
            ->create();

        $this->assertTrue($michael->assigneeOfprojectTasks()->exists());
    }

    /** @test */
    public function it_has_many_timesheets_as_employee(): void
    {
        $michael = Employee::factory()
            ->has(Timesheet::factory()->count(2), 'timesheets')
            ->create();

        $this->assertTrue($michael->timesheets()->exists());
    }

    /** @test */
    public function it_has_many_timesheets_as_approver(): void
    {
        $michael = Employee::factory()
            ->has(Timesheet::factory()->count(2), 'timesheetsAsApprover')
            ->create();

        $this->assertTrue($michael->timesheetsAsApprover()->exists());
    }

    /** @test */
    public function it_gets_the_projects_that_the_employee_leads(): void
    {
        $dwight = Employee::factory()->create();
        Project::factory()->count(2)->create([
            'project_lead_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->projectsAsLead()->exists());
    }

    /** @test */
    public function it_gets_the_project_decisions_written_by_the_employee(): void
    {
        $dwight = Employee::factory()->create();
        ProjectDecision::factory()->count(2)->create([
            'author_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->projectDecisions()->exists());
    }

    /** @test */
    public function it_has_many_consultant_rates(): void
    {
        $dwight = Employee::factory()->create();
        ConsultantRate::factory()->count(2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->consultantRates()->exists());
    }

    /** @test */
    public function it_has_many_files(): void
    {
        $dwight = Employee::factory()->create();
        File::factory()->count(2)->create([
            'uploader_employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->filesUploaded()->exists());
    }

    /** @test */
    public function it_scopes_the_employees_by_the_locked_status(): void
    {
        $dwight = Employee::factory()->create([
            'locked' => true,
        ]);
        $company = $dwight->company;
        Employee::factory(3)->create([
            'company_id' => $company->id,
            'locked' => false,
        ]);

        $this->assertEquals(
            3,
            $company->employees()->notLocked()->count()
        );
    }

    /** @test */
    public function it_returns_the_email_attribute(): void
    {
        $dwight = Employee::factory()->create([]);
        $this->assertEquals(
            'dwigth@dundermifflin.com',
            $dwight->email
        );
    }

    /** @test */
    public function it_returns_the_name_attribute(): void
    {
        $dwight = Employee::factory()->create([]);
        $this->assertEquals(
            'Dwight Schrute',
            $dwight->name
        );
    }

    /** @test */
    public function it_returns_an_object(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $dunder = Company::factory()->create([]);
        $dwight = User::factory()->create([]);
        $position = Position::factory()->create([
            'company_id' => $dunder->id,
            'title' => 'developer',
        ]);
        $pronoun = Pronoun::factory()->create([]);
        $michael = Employee::factory()->create([
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

        $dwight = Employee::factory()->create([
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
        $dwight = Employee::factory()->create([]);
        DirectReport::factory()->count(3)->create([
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
        $dwight = Employee::factory()->create([]);
        DirectReport::factory()->count(3)->create([
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
        $dwight = Employee::factory()->create([
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

        $dwight = Employee::factory()->create([]);
        Worklog::factory()->create([
            'employee_id' => $dwight->id,
            'created_at' => now(),
        ]);

        $this->assertTrue($dwight->hasAlreadyLoggedWorklogToday());

        $dwight = Employee::factory()->create([]);
        Worklog::factory()->create([
            'employee_id' => $dwight->id,
            'created_at' => Carbon::yesterday(),
        ]);
        $this->assertFalse($dwight->hasAlreadyLoggedWorklogToday());
    }

    /** @test */
    public function it_checks_if_a_morale_has_already_been_logged_today(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $dwight = Employee::factory()->create([]);
        Morale::factory()->create([
            'employee_id' => $dwight->id,
            'created_at' => now(),
        ]);

        $this->assertTrue($dwight->hasAlreadyLoggedMoraleToday());

        $dwight = Employee::factory()->create([]);
        Morale::factory()->create([
            'employee_id' => $dwight->id,
            'created_at' => Carbon::yesterday(),
        ]);
        $this->assertFalse($dwight->hasAlreadyLoggedMoraleToday());
    }

    /** @test */
    public function it_returns_the_current_address(): void
    {
        $dwight = Employee::factory()->create();
        Place::factory()->count(2)->create([
            'placable_id' => $dwight->id,
            'placable_type' => 'App\Models\Company\Employee',
        ]);

        $this->assertNull($dwight->getCurrentAddress());

        $place = Place::factory()->create([
            'placable_id' => $dwight->id,
            'placable_type' => 'App\Models\Company\Employee',
            'is_active' => true,
        ]);

        $this->assertNotNull($dwight->getCurrentAddress());
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

        $dwight = Employee::factory()->create();
        CompanyPTOPolicy::factory()->create([
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
        $dwight = Employee::factory()->create();
        $sales = Team::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $sales->employees()->attach(
            $dwight->id,
            [
                'created_at' => Carbon::now('UTC'),
            ]
        );

        $this->assertTrue($dwight->isInTeam($sales->id));

        $dwight = Employee::factory()->create();
        $sales = Team::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $this->assertFalse($dwight->isInTeam($sales->id));
    }

    /** @test */
    public function it_checks_if_the_employee_is_the_manager_of_the_other_employee(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        DirectReport::factory()->create([
            'company_id' => $michael->company_id,
            'manager_id' => $michael->id,
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($michael->isManagerOf($dwight->id));

        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);

        $this->assertFalse($michael->isManagerOf($dwight->id));
    }

    /** @test */
    public function it_checks_if_the_employee_is_in_a_given_project(): void
    {
        $dwight = Employee::factory()->create();
        $api = Project::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $api->employees()->attach($dwight->id);

        $this->assertTrue($dwight->isInProject($api->id));

        $dwight = Employee::factory()->create();
        $api = Project::factory()->create([
            'company_id' => $dwight->company_id,
        ]);

        $this->assertFalse($dwight->isInProject($api->id));
    }
}
