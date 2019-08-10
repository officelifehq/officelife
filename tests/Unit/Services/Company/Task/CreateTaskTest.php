<?php

namespace Tests\Unit\Services\Company\Employee\Team;

use Tests\TestCase;
use App\Jobs\LogTeamAudit;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Task;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use App\Services\Company\Task\CreateTask;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_task() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'assignee_id' => $michael->id,
            'team_id' => $team->id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'company_id' => $michael->company_id,
            'assignee_id' => $michael->id,
            'team_id' => $team->id,
            'title' => 'Fire Jim',
        ]);

        $this->assertInstanceOf(
            Task::class,
            $task
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_associated_to_team' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_assigned_to_employee' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($michael, $task) {
            return $job->notification['action'] === 'task_assigned' &&
                $job->notification['employee_id'] === $michael->id &&
                $job->notification['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });
    }

    /** @test */
    public function it_logs_a_task_with_a_team() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'team_id' => $team->id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertPushed(LogTeamAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_associated_to_team' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertNotPushed(LogEmployeeAudit::class);

        Queue::assertNotPushed(NotifyEmployee::class);
    }

    /** @test */
    public function it_logs_a_task_with_an_assignee() : void
    {
        Queue::fake();

        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
            'assignee_id' => $michael->id,
            'title' => 'Fire Jim',
        ];

        $task = (new CreateTask)->execute($request);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_created' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $task) {
            return $job->auditLog['action'] === 'task_assigned_to_employee' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertPushed(NotifyEmployee::class, function ($job) use ($michael, $task) {
            return $job->notification['action'] === 'task_assigned' &&
                $job->notification['employee_id'] === $michael->id &&
                $job->notification['objects'] === json_encode([
                    'author_id' => $michael->user->id,
                    'author_name' => $michael->user->name,
                    'task_id' => $task->id,
                    'task_name' => $task->name,
                ]);
        });

        Queue::assertNotPushed(LogTeamAudit::class);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $michael = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->user_id,
        ];

        $this->expectException(ValidationException::class);
        (new CreateTask)->execute($request);
    }
}
