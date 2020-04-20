<?php

namespace Tests\Unit\Services\Logs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Models\Company\Employee;
use App\Services\Logs\LogTeamAction;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LogTeamActionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_logs_an_action(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([
            'company_id' => $michael->company_id,
        ]);

        $this->executeService($michael, $team);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'action' => 'team_created',
        ];

        $this->expectException(ValidationException::class);
        (new LogTeamAction)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_author_is_not_in_the_same_company_as_the_team(): void
    {
        $michael = $this->createAdministrator();
        $team = factory(Team::class)->create([]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $team);
    }

    private function executeService(Employee $michael, Team $team): void
    {
        $date = Carbon::now();

        $request = [
            'team_id' => $team->id,
            'action' => 'team_created',
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'objects' => '{"team": 1}',
        ];

        $teamLog = (new LogTeamAction)->execute($request);

        $this->assertDatabaseHas('team_logs', [
            'id' => $teamLog->id,
            'team_id' => $team->id,
            'author_id' => $michael->id,
            'author_name' => $michael->name,
            'audited_at' => $date,
            'action' => 'team_created',
            'objects' => '{"team": 1}',
        ]);

        $this->assertInstanceOf(
            TeamLog::class,
            $teamLog
        );
    }
}
