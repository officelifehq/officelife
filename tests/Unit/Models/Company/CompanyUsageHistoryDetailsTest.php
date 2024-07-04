<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\CompanyUsageHistoryDetails;
use Tests\TestCase;

class CompanyUsageHistoryDetailsTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_company_daily_usage_history(): void
    {
        $details = CompanyUsageHistoryDetails::factory()->create([]);
        $this->assertTrue($details->companyUsageHistory()->exists());
    }
}
