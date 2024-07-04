<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\EmployeePlannedHoliday;
use Tests\ApiTestCase;

class EmployeePlannedHolidayTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $holiday = EmployeePlannedHoliday::factory()->create([]);
        $this->assertTrue($holiday->employee()->exists());
    }
}
