<?php

namespace Tests\Unit\Services\Company\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Services\Company\Team\UnsetTeamLeader;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UnsetTeamLeaderTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_resets_a_team_leader() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'team_id' => $team->id,
        ];

        $team = (new UnsetTeamLeader)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'team_leader_id' => null,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );
    }

    /** @test */
    public function it_logs_an_action() : void
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'team_id' => $team->id,
        ];

        (new UnsetTeamLeader)->execute($request);

        $this->assertdatabasehas('team_logs', [
            'company_id' => $employee->company_id,
            'team_id' => $team->id,
            'action' => 'team_leader_removed',
            'objects' => json_encode([
                'author_id' => $employee->user->id,
                'author_name' => $employee->user->name,
                'team_leader_id' => $team->leader->id,
                'team_leader_name' => $team->leader->name,
            ]),
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $employee = factory(Employee::class)->create([]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
        ];

        $this->expectException(ValidationException::class);
        (new UnsetTeamLeader)->execute($request);
    }
}
