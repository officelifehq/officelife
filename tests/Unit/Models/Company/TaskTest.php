<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Task;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TaskTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_assignee(): void
    {
        $employee = factory(Employee::class)->create([]);
        $task = factory(Task::class)->create([
            'employee_id' => $employee->id,
        ]);
        $this->assertTrue($task->employee()->exists());
    }

    /** @test */
    public function it_only_gets_in_progress_tasks(): void
    {
        factory(Task::class)->create([
            'completed' => true,
        ]);
        factory(Task::class, 2)->create([
            'completed' => false,
        ]);
        $this->assertCount(2, Task::inProgress()->get());
    }
}
