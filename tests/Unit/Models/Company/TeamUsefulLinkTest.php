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

    /** @test */
    public function it_returns_the_label_attribute_if_it_is_defined(): void
    {
        $teamUsefulLink = factory(TeamUsefulLink::class)->create([
            'label' => 'slack',
            'url' => 'https://slack.com',
        ]);

        $this->assertEquals(
            'slack',
            $teamUsefulLink->label
        );
    }

    /** @test */
    public function it_returns_the_url_attribute_if_the_label_is_not_defined(): void
    {
        $teamUsefulLink = factory(TeamUsefulLink::class)->create([
            'label' => null,
            'url' => 'https://slack.com',
        ]);

        $this->assertEquals(
            'https://slack.com',
            $teamUsefulLink->label
        );
    }
}
