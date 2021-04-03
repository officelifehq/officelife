<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimeTrackingEntryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $entry = TimeTrackingEntry::factory()->create();
        $this->assertTrue($entry->project()->exists());
    }

    /** @test */
    public function it_belongs_to_one_employee(): void
    {
        $entry = TimeTrackingEntry::factory()->create();
        $this->assertTrue($entry->employee()->exists());
    }

    /** @test */
    public function it_belongs_to_one_timesheet(): void
    {
        $entry = TimeTrackingEntry::factory()->create();
        $this->assertTrue($entry->timesheet()->exists());
    }

    /** @test */
    public function it_belongs_to_one_project_task(): void
    {
        $entry = TimeTrackingEntry::factory()->create();
        $this->assertTrue($entry->projectTask()->exists());
    }
}
