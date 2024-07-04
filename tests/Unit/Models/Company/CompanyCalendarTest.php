<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\CompanyCalendar;
use Tests\ApiTestCase;

class CompanyCalendarTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_a_company_pto_policy(): void
    {
        $calendar = CompanyCalendar::factory()->create([]);
        $this->assertTrue($calendar->policy()->exists());
    }
}
