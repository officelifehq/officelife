<?php

namespace Tests\Unit\Services\Account\Team;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use App\Services\Account\Team\AddUserToTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddUserToTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_user_to_a_team()
    {
        $author = factory(User::class)->create([]);
        $user = factory(User::class)->create([
            'account_id' => $author->account_id,
        ]);
        $team = factory(Team::class)->create([
            'account_id' => $author->account_id,
        ]);

        $request = [
            'account_id' => $author->account_id,
            'author_id' => $author->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ];

        $team = (new AddUserToTeam)->execute($request);

        $this->assertDatabaseHas('team_user', [
            'account_id' => $user->account_id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ]);

        $this->assertInstanceOf(
            Team::class,
            $team
        );
    }

    /** @test */
    public function it_logs_an_action()
    {
        $author = factory(User::class)->create([]);
        $user = factory(User::class)->create([
            'account_id' => $author->account_id,
        ]);
        $team = factory(Team::class)->create([
            'account_id' => $author->account_id,
        ]);

        $request = [
            'account_id' => $author->account_id,
            'author_id' => $author->id,
            'user_id' => $user->id,
            'team_id' => $team->id,
        ];

        $team = (new AddUserToTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $user->account_id,
            'action' => 'user_added_to_team',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $author = factory(User::class)->create([]);
        $user = factory(User::class)->create([
            'account_id' => $author->account_id,
        ]);
        $team = factory(Team::class)->create([
            'account_id' => $author->account_id,
        ]);

        $request = [
            'account_id' => $author->account_id,
            'author_id' => $author->id,
            'team_id' => $team->id,
        ];

        $this->expectException(ValidationException::class);
        (new AddUserToTeam)->execute($request);
    }
}
