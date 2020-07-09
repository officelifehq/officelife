<?php

namespace Tests\Unit\Services\Company\Task;

use Tests\TestCase;
use App\Jobs\NotifyEmployee;
use App\Models\Company\Task;
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
    public function it_creates_a_task_as_an_administrator(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function it_logs_a_new_time_off_as_hr(): void
    {
        $michael = $this->createHR();
        $dwight = $this->createAnotherEmployee($michael);
        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function normal_user_can_execute_the_service(): void
    {
        $michael = $this->createEmployee();
        $dwight = $this->createAnotherEmployee($michael);

        $this->executeService($michael, $dwight);
    }

    /** @test */
    public function same_user_doesnt_generate_notification_if_he_creates_a_task_with_himself(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael, $michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'michael',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTask)->execute($request);
    }

    private function executeService(Employee $michael, Employee $dwight): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $dwight->id,
            'title' => 'brutal',
        ];

        $task = (new CreateTask)->execute($request);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'employee_id' => $dwight->id,
            'title' => 'brutal',
        ]);

        $this->assertInstanceOf(
            Task::class,
            $task
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $dwight) {
            return $job->auditLog['action'] === 'task_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'title' => 'brutal',
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael) {
            return $job->auditLog['action'] === 'task_created' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'title' => 'brutal',
                ]);
        });

        if ($michael->id != $dwight->id) {
            Queue::assertPushed(NotifyEmployee::class, function ($job) use ($michael) {
                return $job->notification['action'] === 'task_assigned' &&
                $job->notification['objects'] === json_encode([
                    'author_id' => $michael->id,
                    'author_name' => $michael->name,
                    'title' => 'brutal',
                ]);
            });
        } else {
            Queue::assertNotPushed(NotifyEmployee::class);
        }
    }
}
