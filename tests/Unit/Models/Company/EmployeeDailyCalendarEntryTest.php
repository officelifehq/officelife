<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\EmployeeDailyCalendarEntry;
use Tests\ApiTestCase;

class EmployeeDailyCalendarEntryTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $date = EmployeeDailyCalendarEntry::factory()->create([]);
        $this->assertTrue($date->employee()->exists());
    }
}
