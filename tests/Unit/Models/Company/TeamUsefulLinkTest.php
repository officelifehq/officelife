<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\TeamUsefulLink;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamUsefulLinkTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $teamUsefulLink = factory(TeamUsefulLink::class)->create([]);
        $this->assertTrue($teamUsefulLink->team()->exists());
    }
}
