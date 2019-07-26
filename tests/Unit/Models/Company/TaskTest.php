<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company() : void
    {
        $task = factory(Task::class)->create([]);
        $this->assertTrue($task->company()->exists());
    }

    /** @test */
    public function it_belongs_to_a_team() : void
    {
        $team = factory(Team::class)->create([]);
        $task = factory(Task::class)->create([
            'company_id' => $team->company_id,
            'team_id' => $team->id,
        ]);
        $this->assertTrue($task->team()->exists());
    }

    /** @test */
    public function it_belongs_to_an_assignee() : void
    {
        $employee = factory(Employee::class)->create([]);
        $task = factory(Task::class)->create([
            'company_id' => $employee->company_id,
            'assignee_id' => $employee->id,
        ]);
        $this->assertTrue($task->assignee()->exists());
    }
}
