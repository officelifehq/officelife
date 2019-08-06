<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Services\Company\Task\CreateTask;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_task() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'assignee_id' => $employee->id,
            'team_id' => $team->id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'company_id' => $employee->company_id,
            'assignee_id' => $employee->id,
            'team_id' => $team->id,
            'title' => 'Fire Jim',
        ]);

        $this->assertInstanceOf(
            Task::class,
            $task
        );
    }

    /** @test */
    public function it_logs_a_task() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'task_created',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseMissing('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'task_associated_to_team',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseMissing('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned_to_employee',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);
    }

    /** @test */
    public function it_logs_a_task_with_a_team() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'team_id' => $team->id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'task_created',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseHas('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'task_associated_to_team',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseMissing('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned_to_employee',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);
    }

    /** @test */
    public function it_logs_a_task_with_an_assignee() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'assignee_id' => $employee->id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'task_created',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseMissing('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'task_associated_to_team',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned_to_employee',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'task_id' => $task->id,
                'task_name' => $task->name,
            ]),
        ]);

        $this->assertDatabaseHas('notifications', [
            'company_id' => $employee->company_id,
            'user_id' => $employee->user_id,
            'action' => 'task_assigned',
            'content' => $task->title,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateTask)->execute($request);
    }
}
