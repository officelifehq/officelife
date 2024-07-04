<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\MoraleCompanyHistory;
use Tests\ApiTestCase;

class MoraleCompanyHistoryTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $moraleCompanyHistory = MoraleCompanyHistory::factory()->create([]);
        $this->assertTrue($moraleCompanyHistory->company()->exists());
    }
}
