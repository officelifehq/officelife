<?php

namespace Tests\Integration;

use App\Models\User\User;
use Tests\TestCase;

class SetupFrontEndTestUserTest extends TestCase
{
    /** @test */
    public function it_creates_a_user_for_frontend_tests(): void
    {
        $userCount = User::count();

        $this->artisan('setup:frontendtestuser')
            ->assertExitCode(0);

        $this->assertEquals($userCount + 1, User::count());
    }
}
