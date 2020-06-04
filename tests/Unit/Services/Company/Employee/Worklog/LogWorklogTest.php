<?php

namespace Tests\Unit\Services\Company\Employee\Worklog;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Jobs\LogEmployeeAudit;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Exceptions\WorklogAlreadyLoggedTodayException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogWorklogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_worklog_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $this->executeService($michael);
    }

    /** @test */
    public function it_logs_a_worklog_as_hr(): void
    {
        $michael = $this->createHR();
        $this->executeService($michael);
    }

    /** @test */
    public function it_logs_a_worklog_as_normal_user(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);
    }

    /** @test */
    public function it_logs_a_worklog_and_resets_the_counter_of_missed_worklog(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);

        $this->assertDatabaseHas('employees', [
            'id' => $michael->id,
            'consecutive_worklog_missed' => 0,
        ]);
    }

    /** @test */
    public function it_doesnt_let_record_a_worklog_if_one_has_already_been_submitted_today(): void
    {
        $michael = $this->createEmployee();
        $this->executeService($michael);

        $this->expectException(WorklogAlreadyLoggedTodayException::class);
        $this->executeService($michael);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LogWorklog)->execute($request);
    }

    private function executeService(Employee $michael): void
    {
        Queue::fake();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'employee_id' => $michael->id,
            'content' => 'I have sold paper',
        ];

        $worklog = (new LogWorklog)->execute($request);

        $this->assertDatabaseHas('worklogs', [
            'id' => $worklog->id,
            'employee_id' => $michael->id,
            'content' => 'I have sold paper',
        ]);

        $this->assertInstanceOf(
            Worklog::class,
            $worklog
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($michael, $worklog) {
            return $job->auditLog['action'] === 'employee_worklog_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'worklog_id' => $worklog->id,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($michael, $worklog) {
            return $job->auditLog['action'] === 'employee_worklog_logged' &&
                $job->auditLog['author_id'] === $michael->id &&
                $job->auditLog['objects'] === json_encode([
                    'employee_id' => $michael->id,
                    'employee_name' => $michael->name,
                    'worklog_id' => $worklog->id,
                ]);
        });
    }
}
