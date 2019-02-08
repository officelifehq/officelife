<?php

namespace Tests\Unit\Services\Company\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Services\Company\Team\UpdateTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team()
    {
        $team = factory(Team::class)->create([]);
        $employee = factory(Employee::class)->create([
            'company_id' => $team->company_id,
        ]);

        $request = [
            'company_id' => $team->company_id,
            'author_id' => $employee->user->id,
            'team_id' => $team->id,
            'name' => 'Selling team',
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'company_id' => $team->company_id,
            'name' => 'Selling team',
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );
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
            'name' => 'Selling team',
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $team->company_id,
            'action' => 'team_updated',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeam)->execute($request);
    }
}
