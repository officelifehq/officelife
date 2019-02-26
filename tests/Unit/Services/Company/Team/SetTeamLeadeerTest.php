<?php

namespace Tests\Unit\Services\Company\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Services\Company\Team\SetTeamLeader;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetTeamLeaderTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_sets_someone_as_team_leader()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'employee_id' => $employee->id,
            'team_id' => $team->id,
        ];

        $team = (new SetTeamLeader)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'team_leader_id' => $employee->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $employee->company_id,
            'action' => 'team_leader_assigned',
        ]);
    }

    /** @test */
    public function it_resets_the_team_leader()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'employee_id' => null,
            'team_id' => $team->id,
        ];

        $team = (new SetTeamLeader)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'team_leader_id' => null,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $employee = factory(Employee::class)->create([]);
        $team = factory(Team::class)->create([
            'company_id' => $employee->company_id,
        ]);

        $request = [
            'company_id' => $employee->company_id,
            'author_id' => $employee->user_id,
            'employee_id' => $employee->id,
        ];

        $this->expectException(ValidationException::class);
        (new SetTeamLeader)->execute($request);
    }
}
