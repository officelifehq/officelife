<?php

namespace Tests\Unit\Services\Company\Employee\Worklog;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Employee\Worklog\LogWorklog;
use App\Exceptions\WorklogAlreadyLoggedTodayException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogWorklogTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_a_worklog()
    {
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
    }

    /** @test */
    public function it_logs_a_worklog_and_resets_the_counter_of_missed_worklog()
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
    public function it_logs_an_action()
    {
        $dwight = factory(Employee::class)->create([]);

        $request = [
            'author_id' => $dwight->user_id,
            'employee_id' => $dwight->id,
            'content' => 'I have sold paper',
        ];

        (new LogWorklog)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $dwight->company_id,
            'action' => 'employee_worklog_logged',
        ]);

        $this->assertDatabaseHas('employee_logs', [
            'company_id' => $dwight->company_id,
            'action' => 'worklog_logged',
        ]);
    }

    /** @test */
    public function it_doesnt_let_record_a_worklog_if_one_has_already_been_submitted_today()
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
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new LogWorklog)->execute($request);
    }
}
