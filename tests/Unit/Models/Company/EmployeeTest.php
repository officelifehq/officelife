<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
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
}
