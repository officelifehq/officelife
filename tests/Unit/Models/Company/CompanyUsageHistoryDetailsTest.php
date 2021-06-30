<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CompanyUsageHistoryDetails;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyUsageHistoryDetailsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company_daily_usage_history(): void
    {
        $details = CompanyUsageHistoryDetails::factory()->create([]);
        $this->assertTrue($details->companyUsageHistory()->exists());
    }
}
