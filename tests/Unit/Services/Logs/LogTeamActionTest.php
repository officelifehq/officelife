<?php

namespace Tests\Unit\Services\Logs;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Services\Logs\LogTeamAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LogTeamActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action() : void
    {
        $team = factory(Team::class)->create([]);

        $request = [
            'team_id' => $team->id,
            'action' => 'team_created',
            'objects' => '{"team": 1}',
        ];

        $teamLog = (new LogTeamAction)->execute($request);

        $this->assertDatabaseHas('team_logs', [
            'id' => $teamLog->id,
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
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'action' => 'team_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogTeamAction)->execute($request);
    }
}
