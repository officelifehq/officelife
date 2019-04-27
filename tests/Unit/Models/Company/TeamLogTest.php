<?php

namespace Tests\Unit\Models\Company;

use Tests\ApiTestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamLogTest extends ApiTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company()
    {
        $TeamLog = factory(TeamLog::class)->create([]);
        $this->assertTrue($TeamLog->company()->exists());
    }

    /** @test */
    public function it_belongs_to_a_team()
    {
        $TeamLog = factory(TeamLog::class)->create([]);
        $this->assertTrue($TeamLog->team()->exists());
    }

    /** @test */
    public function it_returns_the_date_attribute()
    {
        $TeamLog = factory(TeamLog::class)->create([
            'created_at' => '2017-01-22 17:56:03',
        ]);
        $this->assertEquals(
            'Jan 22, 2017 17:56',
            $TeamLog->date
        );
    }

    /** @test */
    public function it_returns_the_object_attribute()
    {
        $TeamLog = factory(TeamLog::class)->create([]);
        $this->assertEquals(
            1,
            $TeamLog->object->{'user'}
        );
    }

    /** @test */
    public function it_returns_the_author_attribute()
    {
        $adminEmployee = $this->createAdministrator();

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $TeamLog = factory(TeamLog::class)->create([
            'objects' => json_encode([
                'author_id' => $adminEmployee->user->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$adminEmployee->user->id).'">'.$adminEmployee->user->name.'</a>',
            $TeamLog->author
        );

        $TeamLog = factory(TeamLog::class)->create([
            'objects' => json_encode([
                'author_id' => 12345,
                'author_name' => 'Dwight Schrute',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'Dwight Schrute',
            $TeamLog->author
        );
    }

    /** @test */
    public function it_returns_the_team_attribute()
    {
        $adminEmployee = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $adminEmployee->company_id,
        ]);

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $TeamLog = factory(TeamLog::class)->create([
            'objects' => json_encode([
                'team_id' => $team->id,
            ]),
            'company_id' => $team->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/teams/'.$team->id).'">'.$team->name.'</a>',
            $TeamLog->team
        );

        $TeamLog = factory(TeamLog::class)->create([
            'objects' => json_encode([
                'team_id' => 12345,
                'team_name' => 'Sales',
            ]),
            'company_id' => $team->company_id,
        ]);

        $this->assertEquals(
            'Sales',
            $TeamLog->team
        );
    }

    /** @test */
    public function it_returns_the_team_leader_attribute()
    {
        $adminEmployee = $this->createAdministrator();

        Cache::shouldReceive('get')
            ->once()
            ->times(2)
            ->with('currentCompany')
            ->andReturn($adminEmployee->company);

        $TeamLog = factory(TeamLog::class)->create([
            'objects' => json_encode([
                'team_leader_id' => $adminEmployee->id,
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            '<a href="'.tenant('/employees/'.$adminEmployee->id).'">'.$adminEmployee->name.'</a>',
            $TeamLog->teamLeader
        );

        $TeamLog = factory(TeamLog::class)->create([
            'objects' => json_encode([
                'team_leader_id' => 12345,
                'team_leader_name' => 'Dwight Schrute',
            ]),
            'company_id' => $adminEmployee->company_id,
        ]);

        $this->assertEquals(
            'Dwight Schrute',
            $TeamLog->teamLeader
        );
    }
}
