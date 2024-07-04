<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\Timesheet;
use App\Models\Company\TimeTrackingEntry;
use Tests\TestCase;

class TimesheetTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $timesheet = Timesheet::factory()->create();
        $this->assertTrue($timesheet->company()->exists());
    }

    /** @test */
    public function it_belongs_to_one_employee(): void
    {
        $timesheet = Timesheet::factory()->create();
        $this->assertTrue($timesheet->employee()->exists());
    }

    /** @test */
    public function it_belongs_to_one_approver(): void
    {
        $timesheet = Timesheet::factory()->create([
            'approver_id' => $this->createAdministrator()->id,
        ]);
        $this->assertTrue($timesheet->approver()->exists());
    }

    /** @test */
    public function it_has_many_time_tracking_entries(): void
    {
        $timesheet = Timesheet::factory()
            ->has(TimeTrackingEntry::factory()->count(2), 'timeTrackingEntries')
            ->create();

        $this->assertTrue($timesheet->timeTrackingEntries()->exists());
    }
}
