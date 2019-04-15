<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_company()
    {
        $team = factory(Team::class)->create([]);
        $this->assertTrue($team->company()->exists());
    }

    /** @test */
    public function it_has_many_employees()
    {
        $team = factory(Team::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);
        $employeeB = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $team->employees()->sync([$employee->id => ['company_id' => $team->company_id]]);
        $team->employees()->sync([$employeeB->id => ['company_id' => $team->company_id]]);

        $this->assertTrue($team->employees()->exists());
    }

    /** @test */
    public function it_has_a_leader()
    {
        $team = factory(Team::class)->create([]);
        $this->assertTrue($team->leader()->exists());
    }
}
