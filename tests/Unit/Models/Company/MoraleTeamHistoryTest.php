<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\MoraleTeamHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoraleTeamHistoryTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $moraleTeamHistory = factory(MoraleTeamHistory::class)->create([]);
        $this->assertTrue($moraleTeamHistory->team()->exists());
    }
}
