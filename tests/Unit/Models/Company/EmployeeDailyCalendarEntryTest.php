<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeeDailyCalendarEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeeDailyCalendarEntryTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $date = EmployeeDailyCalendarEntry::factory()->create([]);
        $this->assertTrue($date->employee()->exists());
    }
}
