<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\MoraleCompanyHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoraleCompanyHistoryTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company() :void
    {
        $moraleCompanyHistory = factory(MoraleCompanyHistory::class)->create([]);
        $this->assertTrue($moraleCompanyHistory->company()->exists());
    }
}
