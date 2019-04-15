<?php

namespace Tests\Unit\Services\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Services\Team\LogTeamAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogTeamActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action()
    {
        $team = factory(Team::class)->create([]);

        $request = [
            'company_id' => $team->company_id,
            'team_id' => $team->id,
            'action' => 'team_created',
            'objects' => '{"team": 1}',
        ];

        $teamLog = (new LogTeamAction)->execute($request);

        $this->assertDatabaseHas('team_logs', [
            'id' => $teamLog->id,
            'company_id' => $team->company_id,
            'team_id' => $team->id,
            'action' => 'team_created',
            'objects' => '{"team": 1}',
        ]);

        $this->assertInstanceOf(
            TeamLog::class,
            $teamLog
        );
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'action' => 'team_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogTeamAction)->execute($request);
    }
}
