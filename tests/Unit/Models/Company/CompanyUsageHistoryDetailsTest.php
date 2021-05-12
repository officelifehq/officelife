<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CompanyUsageHistoryDetails;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyUsageHistoryDetailsTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $usage = CompanyUsageHistoryDetails::factory()->create([]);
        $this->assertTrue($usage->company()->exists());
    }
}
