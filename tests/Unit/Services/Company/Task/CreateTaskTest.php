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
    public function it_creates_a_task()
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
    public function it_logs_a_task()
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

        (new CreateTask)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'task_created',
        ]);

        $this->assertDatabaseMissing('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'task_associated_to_team',
        ]);

        $this->assertDatabaseMissing('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned_to_employee',
        ]);
    }

    /** @test */
    public function it_logs_a_task_with_a_team()
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

        (new CreateTask)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'task_created',
        ]);

        $this->assertDatabaseHas('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'task_associated_to_team',
        ]);

        $this->assertDatabaseMissing('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned_to_employee',
        ]);
    }

    /** @test */
    public function it_logs_a_task_with_an_assignee()
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

        (new CreateTask)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'task_created',
        ]);

        $this->assertDatabaseMissing('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'task_associated_to_team',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $employee->company_id,
            'employee_id' => $employee->id,
            'action' => 'task_assigned_to_employee',
        ]);

        $this->assertDatabaseHas('notifications', [
            'company_id' => $employee->company_id,
            'user_id' => $employee->user_id,
            'action' => 'task_assigned',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
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
