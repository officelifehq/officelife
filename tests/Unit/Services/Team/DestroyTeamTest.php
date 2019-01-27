<?php

namespace Tests\Unit\Services\Account\Team;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use App\Services\Account\Team\DestroyTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DestroyTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_destroys_a_team()
    {
        $team = factory(Team::class)->create([]);
        $author = factory(User::class)->create([
            'account_id' => $team->account_id,
        ]);

        $request = [
            'account_id' => $team->account_id,
            'author_id' => $author->id,
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
        $author = factory(User::class)->create([
            'account_id' => $team->account_id,
        ]);

        $request = [
            'account_id' => $team->account_id,
            'author_id' => $author->id,
            'team_id' => $team->id,
        ];

        (new DestroyTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $team->account_id,
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
