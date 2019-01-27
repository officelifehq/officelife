<?php

namespace Tests\Unit\Services\Account\Team;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use App\Models\Account\Account;
use App\Services\Account\Team\CreateTeam;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateTeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_team()
    {
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'name' => 'Selling team',
            'description' => 'Selling paper everyday',
        ];

        $team = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'account_id' => $user->account_id,
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
        $user = factory(User::class)->create([]);

        $request = [
            'account_id' => $user->account_id,
            'author_id' => $user->id,
            'name' => 'Selling team',
            'description' => 'Selling paper everyday',
        ];

        $team = (new CreateTeam)->execute($request);

        $this->assertDatabaseHas('audit_logs', [
            'account_id' => $user->account_id,
            'action' => 'team_created',
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given()
    {
        $account = factory(Account::class)->create([]);

        $request = [
            'name' => 'Selling team',
        ];

        $this->expectException(ValidationException::class);
        (new CreateTeam)->execute($request);
    }
}
