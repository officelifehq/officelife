<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\MoraleTeamHistory;
use Tests\ApiTestCase;

class MoraleTeamHistoryTest extends ApiTestCase
{
    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $moraleTeamHistory = MoraleTeamHistory::factory()->create([]);
        $this->assertTrue($moraleTeamHistory->team()->exists());
    }
}
