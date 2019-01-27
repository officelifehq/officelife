<?php

namespace Tests\Unit\Services\Account\Team;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use App\Services\Account\Team\UpdateTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_updates_a_team()
    {
        $team = factory(Team::class)->create([]);
        $user = factory(User::class)->create([
            'account_id' => $team->account_id,
        ]);

        $request = [
            'account_id' => $team->account_id,
            'author_id' => $user->id,
            'team_id' => $team->id,
            'name' => 'Selling team',
            'description' => 'Selling paper everyday',
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'account_id' => $team->account_id,
            'name' => 'Selling team',
            'description' => 'Selling paper everyday',
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
        $user = factory(User::class)->create([
            'account_id' => $team->account_id,
        ]);

        $request = [
            'account_id' => $team->account_id,
            'author_id' => $user->id,
            'team_id' => $team->id,
            'name' => 'Selling team',
            'description' => 'Selling paper everyday',
        ];

        (new UpdateTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $team->account_id,
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
