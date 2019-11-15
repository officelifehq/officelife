<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetupFrontEndTestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_setups_the_front_end_environnement(): void
    {
        $this->artisan('setup:frontendtesting')
            ->assertExitCode(0);
    }
}
