<?php

namespace Tests\Unit\Services\Company\Employee\Worklog;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Jobs\Logs\LogAccountAudit;
use App\Jobs\Logs\LogEmployeeAudit;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Exceptions\WorklogAlreadyLoggedTodayException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogWorklogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_worklog() : void
    {
        Queue::fake();

        $dwight = factory(Employee::class)->create([]);

        $request = [
            'author_id' => $dwight->user_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ];

        $worklog = (new LogWorklog)->execute($request);

        $this->assertDatabaseHas('worklog', [
            'id' => $worklog->id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ]);

        $this->assertInstanceOf(
            Worklog::class,
            $worklog
        );

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($dwight, $worklog) {
            return $job->auditLog['action'] === 'employee_worklog_logged' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $dwight->user->id,
                    'author_name' => $dwight->user->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'worklog_id' => $worklog->id,
                ]);
        });

        Queue::assertPushed(LogEmployeeAudit::class, function ($job) use ($dwight, $worklog) {
            return $job->auditLog['action'] === 'employee_worklog_logged' &&
                $job->auditLog['objects'] === json_encode([
                    'author_id' => $dwight->user->id,
                    'author_name' => $dwight->user->name,
                    'employee_id' => $dwight->id,
                    'employee_name' => $dwight->name,
                    'worklog_id' => $worklog->id,
                ]);
        });
    }

    /** @test */
    public function it_logs_a_worklog_and_resets_the_counter_of_missed_worklog() : void
    {
        $dwight = factory(Employee::class)->create([
            'consecutive_worklog_missed' => 4,
        ]);

        $request = [
            'author_id' => $dwight->user_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ];

        $worklog = (new LogWorklog)->execute($request);

        $this->assertDatabaseHas('employees', [
            'id' => $dwight->id,
            'consecutive_worklog_missed' => 0,
        ]);
    }

    /** @test */
    public function it_doesnt_let_record_a_worklog_if_one_has_already_been_submitted_today() : void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $dwight = factory(Employee::class)->create([]);
        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => now(),
        ]);

        $request = [
            'author_id' => $dwight->user_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ];

        $this->expectException(WorklogAlreadyLoggedTodayException::class);
        (new LogWorklog)->execute($request);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LogWorklog)->execute($request);
    }
}
