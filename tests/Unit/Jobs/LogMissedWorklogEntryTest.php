<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use App\Jobs\LogMissedWorklogEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogMissedWorklogEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_employee_missing_a_worklog(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        factory(Employee::class)->create([
            'consecutive_worklog_missed' => 0,
        ]);

        LogMissedWorklogEntry::dispatch(Carbon::today());

        $numberOfEmployees = Employee::where('consecutive_worklog_missed', '!=', 0)->count();

        $this->assertEquals(
            1,
            $numberOfEmployees
        );
    }

    /** @test */
    public function it_doesnt_log_a_missing_worklog_day_if_the_employee_has_logged_today(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $dwight = factory(Employee::class)->create([
            'consecutive_worklog_missed' => 0,
        ]);

        factory(Worklog::class)->create([
            'employee_id' => $dwight->id,
            'created_at' => '2018-01-01',
        ]);

        LogMissedWorklogEntry::dispatch(Carbon::today());

        $numberOfEmployees = Employee::where('consecutive_worklog_missed', '!=', 0)->count();

        $this->assertEquals(
            0,
            $numberOfEmployees
        );
    }
}
