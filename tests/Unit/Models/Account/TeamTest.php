<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
use App\Models\Account\Team;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TeamTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_account()
    {
        $team = factory(Team::class)->create([]);
        $this->assertTrue($team->account()->exists());
    }

    /** @test */
    public function it_has_many_users()
    {
        $team = factory(Team::class)->create([]);
        $user = factory(User::class)->create([
            'account_id' => $team->account_id,
        ]);
        $userB = factory(User::class)->create([
            'account_id' => $team->account_id,
        ]);

        $team->users()->sync([$user->id => ['account_id' => $team->account_id]]);
        $team->users()->sync([$userB->id => ['account_id' => $team->account_id]]);

        $this->assertTrue($team->users()->exists());
    }
}
