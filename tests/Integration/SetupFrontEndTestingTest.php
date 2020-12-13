<?php

namespace Tests\Integration;

use Tests\TestCase;

class SetupFrontEndTestingTest extends TestCase
{
    /** @test */
    public function it_setups_the_front_end_environnement(): void
    {
        $this->artisan('setup:frontendtesting')
            ->assertExitCode(0);
    }
}
