<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Models\Company\Employee;
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
    public function it_returns_an_object(): void
    {
        $michael = Employee::factory()->create([
            'first_name' => 'michael',
            'last_name' => 'scott',
        ]);
        $sales = Team::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $log = TeamLog::factory()->create([
            'team_id' => $sales->id,
            'author_id' => $michael->id,
            'author_name' => 'michael scott',
            'action' => 'account_created',
            'audited_at' => '2020-01-12 00:00:00',
        ]);

        $this->assertEquals(
            [
                'id' => $log->id,
                'action' => 'account_created',
                'objects' => json_decode('{"user": 1}'),
                'localized_content' => '',
                'author' => [
                    'id' => $michael->id,
                    'name' => 'michael scott',
                ],
                'localized_audited_at' => 'Jan 12, 2020 00:00',
                'audited_at' => '2020-01-12 00:00:00',
            ],
            $log->toObject()
        );
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
