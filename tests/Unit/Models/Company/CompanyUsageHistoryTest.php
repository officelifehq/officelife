<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CompanyUsageHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyUsageHistoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $usage = CompanyUsageHistory::factory()->create([]);
        $this->assertTrue($usage->company()->exists());
    }
}
