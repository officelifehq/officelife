<?php

namespace Tests\Unit\Models\User;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use App\Models\Company\DirectReport;
use App\Models\Company\EmployeeEvent;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_user() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertTrue($dwight->user()->exists());
    }

    /** @test */
    public function it_belongs_to_a_company() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertTrue($dwight->company()->exists());
    }

    /** @test */
    public function it_has_many_teams() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);
        $teamB = factory(Team::class)->create([
            'company_id' => $dwight->company_id,
        ]);

        $dwight->teams()->sync([$team->id => ['company_id' => $dwight->company_id]]);
        $dwight->teams()->sync([$teamB->id => ['company_id' => $dwight->company_id]]);

        $this->assertTrue($dwight->teams()->exists());
    }

    /** @test */
    public function it_has_many_direct_reports() : void
    {
        $manager = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'manager_id' => $manager->id,
        ]);

        $this->assertTrue($manager->managerOf()->exists());
    }

    /** @test */
    public function it_has_many_managers() : void
    {
        $dwight = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->reportsTo()->exists());
    }

    /** @test */
    public function it_has_many_logs() : void
    {
        $dwight = factory(Employee::class)->create();
        factory(EmployeeLog::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->employeeLogs()->exists());
    }

    /** @test */
    public function it_has_one_position() : void
    {
        $dwight = factory(Employee::class)->create();

        $this->assertTrue($dwight->position()->exists());
    }

    /** @test */
    public function it_has_many_employee_events() : void
    {
        $dwight = factory(Employee::class)->create();
        factory(EmployeeEvent::class, 2)->create([
            'employee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->employeeEvents()->exists());
    }

    /** @test */
    public function it_has_many_tasks() : void
    {
        $dwight = factory(Employee::class)->create();
        factory(Task::class, 2)->create([
            'assignee_id' => $dwight->id,
        ]);

        $this->assertTrue($dwight->tasks()->exists());
    }

    /** @test */
    public function it_has_many_worklog() : void
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
    public function it_returns_the_email_attribute() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertEquals(
            'dwigth@dundermifflin.com',
            $dwight->email
        );
    }

    /** @test */
    public function it_returns_the_name_attribute() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertEquals(
            'Dwight Schrute',
            $dwight->name
        );
    }

    /** @test */
    public function it_returns_the_birthdate_attribute() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertEquals(
            '1978-01-20 00:00:00',
            $dwight->birthdate
        );
    }

    /** @test */
    public function it_get_the_list_of_the_employees_managers() : void
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
    public function it_get_the_list_of_the_employees_direct_reports() : void
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
    public function it_gets_the_path_for_the_invitation_link() : void
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
    public function it_checks_whether_the_invitation_has_been_accepted() : void
    {
        $dwight = factory(Employee::class)->create([]);
        $this->assertFalse($dwight->invitationAlreadyAccepted());

        $dwight = factory(Employee::class)->create([
            'invitation_used_at' => '1999-01-01',
        ]);
        $this->assertTrue($dwight->invitationAlreadyAccepted());
    }

    /** @test */
    public function it_checks_if_a_worklog_has_already_been_logged_today() : void
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
}
