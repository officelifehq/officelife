<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetupFrontEndTestUserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_user_for_frontend_tests(): void
    {
        $userCount = User::count();

        $this->artisan('setup:frontendtestuser')
            ->assertExitCode(0);

        $this->assertEquals($userCount + 1, User::count());
    }
}
