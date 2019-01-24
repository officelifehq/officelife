<?php

namespace Tests\Unit\Models\User;

use Tests\TestCase;
use App\Models\User\User;
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
}
