<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\TeamLog;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $teamLog = TeamLog::factory()->create([]);
        $this->assertTrue($teamLog->team()->exists());
    }

    /** @test */
    public function it_belongs_to_an_author(): void
    {
        $teamLog = TeamLog::factory()->create([]);
        $this->assertTrue($teamLog->author()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute(): void
    {
        $teamLog = TeamLog::factory()->create([
            'audited_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $teamLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute(): void
    {
        $teamLog = TeamLog::factory()->create([]);
        $this->assertEquals(
            1,
            $teamLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_content_attribute(): void
    {
        $teamLog = TeamLog::factory()->create([
            'action' => 'team_updated',
            'objects' => json_encode([
                'team_old_name' => 'Sales',
                'team_new_name' => 'Product',
            ]),
        ]);

        $this->assertEquals(
            'Changed the name from Sales to Product.',
            $teamLog->content
        );
    }
}
