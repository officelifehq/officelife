<?php

namespace Tests\Integration;

use Tests\TestCase;
use App\Models\User\User;

class SetupFrontEndTestingTest extends TestCase
{
    /** @test */
    public function it_setups_the_front_end_environnement(): void
    {
        $this->artisan('setup:frontendtesting')
            ->assertExitCode(0);
    }
}
