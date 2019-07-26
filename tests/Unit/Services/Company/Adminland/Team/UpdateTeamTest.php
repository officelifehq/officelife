<?php

namespace Tests\Unit\Services\Company\Adminland\Team;

use Tests\TestCase;
use App\Models\Company\Team;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Services\Company\Adminland\Team\UpdateTeam;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team() : void
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
    public function it_logs_an_action() : void
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
    public function it_fails_if_wrong_parameters_are_given() : void
    {
        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new UpdateTeam)->execute($request);
    }
}
