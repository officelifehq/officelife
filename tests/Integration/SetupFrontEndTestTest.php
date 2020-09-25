<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetupFrontEndTestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_setups_the_front_end_environnement(): void
    {
        $this->withoutMockingConsoleOutput();

        $userCount = User::count();

        $this->artisan('setup:frontendtesting')
            ->assertExitCode(0);

        $this->assertEquals($userCount + 1, User::count());
    }
}
