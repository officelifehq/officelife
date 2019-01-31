<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_account()
    {
        $user = factory(User::class)->create([]);
        $this->assertTrue($user->account()->exists());
    }

    /** @test */
    public function it_has_many_teams()
    {
        $user = factory(User::class)->create([]);
        $team = factory(Team::class)->create([
            'account_id' => $user->account_id,
        ]);
        $teamB = factory(Team::class)->create([
            'account_id' => $user->account_id,
        ]);

        $user->teams()->sync([$team->id => ['account_id' => $user->account_id]]);
        $user->teams()->sync([$teamB->id => ['account_id' => $user->account_id]]);

        $this->assertTrue($user->teams()->exists());
    }

    /** @test */
    public function it_returns_the_name_attribute()
    {
        $user = new User;
        $user->email = 'dwight@dundermifflin.com';

        $this->assertEquals(
            $user->name,
            'dwight@dundermifflin.com'
        );

        $user->first_name = 'Dwight';

        $this->assertEquals(
            $user->name,
            'Dwight'
        );

        $user->last_name = 'Schrute';

        $this->assertEquals(
            $user->name,
            'Dwight Schrute'
        );
    }
}
