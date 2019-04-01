<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use App\Models\Company\DirectReport;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $employee = factory(Employee::class)->create([]);
        $this->assertTrue($employee->user()->exists());
    }

    /** @test */
    public function it_belongs_to_a_company()
    {
        $employee = factory(Employee::class)->create([]);
        $this->assertTrue($employee->company()->exists());
    }

    /** @test */
    public function it_has_many_teams()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);
        $teamB = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $employee->teams()->sync([$team->id => ['company_id' => $employee->company_id]]);
        $employee->teams()->sync([$teamB->id => ['company_id' => $employee->company_id]]);

        $this->assertTrue($employee->teams()->exists());
    }

    /** @test */
    public function it_has_many_direct_reports()
    {
        $manager = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'manager_id' => $manager->id,
        ]);

        $this->assertTrue($manager->managerOf()->exists());
    }

    /** @test */
    public function it_has_many_managers()
    {
        $employee = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'employee_id' => $employee->id,
        ]);

        $this->assertTrue($employee->reportsTo()->exists());
    }

    /** @test */
    public function it_has_many_logs()
    {
        $employee = factory(Employee::class)->create();
        factory(EmployeeLog::class, 2)->create([
            'employee_id' => $employee->id,
        ]);

        $this->assertTrue($employee->employeeLogs()->exists());
    }

    /** @test */
    public function it_returns_the_email_attribute()
    {
        $employee = factory(Employee::class)->create([]);
        $this->assertEquals(
            'dwigth@dundermifflin.com',
            $employee->email
        );
    }

    /** @test */
    public function it_returns_the_name_attribute()
    {
        $employee = factory(Employee::class)->create([]);
        $this->assertEquals(
            'Dwight Schrute',
            $employee->name
        );
    }

    /** @test */
    public function it_returns_the_birthdate_attribute()
    {
        $employee = factory(Employee::class)->create([]);
        $this->assertEquals(
            '1978-01-20 00:00:00',
            $employee->birthdate
        );
    }

    /** @test */
    public function it_get_the_list_of_the_employees_managers()
    {
        $employee = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
        ]);

        $this->assertEquals(
            3,
            $employee->getListOfManagers()->count()
        );
    }

    /** @test */
    public function it_get_the_list_of_the_employees_direct_reports()
    {
        $employee = factory(Employee::class)->create([]);
        factory(DirectReport::class, 3)->create([
            'company_id' => $employee->company_id,
            'manager_id' => $employee->id,
        ]);

        $this->assertEquals(
            3,
            $employee->getListOfDirectReports()->count()
        );
    }

    /** @test */
    public function it_gets_the_path_for_the_invitation_link()
    {
        $employee = factory(Employee::class)->create([
            'invitation_link' => 'dunder',
        ]);

        $this->assertEquals(
            config('app.url').'/invite/employee/dunder',
            $employee->getPathInvitationLink()
        );
    }

    /** @test */
    public function it_checks_whether_the_invitation_has_been_accepted()
    {
        $employee = factory(Employee::class)->create([]);
        $this->assertFalse($employee->invitationAlreadyAccepted());

        $employee = factory(Employee::class)->create([
            'invitation_used_at' => '1999-01-01',
        ]);
        $this->assertTrue($employee->invitationAlreadyAccepted());
    }
}
