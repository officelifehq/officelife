<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Employee;
use App\Models\Company\Task;
use Tests\ApiTestCase;

class TaskTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_an_assignee(): void
    {
        $employee = Employee::factory()->create([]);
        $task = Task::factory()->create([
            'employee_id' => $employee->id,
        ]);
        $this->assertTrue($task->employee()->exists());
    }

    /** @test */
    public function it_only_gets_in_progress_tasks(): void
    {
        Task::factory()->create([
            'completed' => true,
        ]);
        Task::factory()->count(2)->create([
            'completed' => false,
        ]);
        $this->assertCount(2, Task::inProgress()->get());
    }
}
