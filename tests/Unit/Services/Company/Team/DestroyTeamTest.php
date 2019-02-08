<?php

namespace Tests\Unit\Services\Company\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Services\Company\Team\DestroyTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DestroyTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_team()
    {
        $team = factory(Team::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $request = [
            'company_id' => $team->company_id,
            'author_id' => $employee->user->id,
            'team_id' => $team->id,
        ];

        (new DestroyTeam)->execute($request);

        $this->assertDatabaseMissing('teams', [
            'id' => $team->id,
        ]);
    }

    /** @test */
    public function it_logs_an_action()
    {
        $team = factory(Team::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $request = [
            'company_id' => $team->company_id,
            'author_id' => $employee->user->id,
            'team_id' => $team->id,
        ];

        (new DestroyTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $team->company_id,
            'action' => 'team_destroyed',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyTeam)->execute($request);
    }
}
