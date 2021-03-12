<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\EmployeePlannedHoliday;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmployeePlannedHolidayTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_an_employee(): void
    {
        $holiday = EmployeePlannedHoliday::factory()->create([]);
        $this->assertTrue($holiday->employee()->exists());
    }
}
