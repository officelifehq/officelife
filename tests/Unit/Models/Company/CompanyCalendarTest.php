<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\CompanyCalendar;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyCalendarTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company_pto_policy() : void
    {
        $calendar = factory(CompanyCalendar::class)->create([]);
        $this->assertTrue($calendar->policy()->exists());
    }
}
