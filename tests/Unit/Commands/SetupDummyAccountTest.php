<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetupDummyAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_setups_the_front_end_environnement(): void
    {
        $this->artisan('setup:dummyaccount --silent')
            ->expectsQuestion('Are you sure you want to proceed? This will delete ALL data in your environment.', 'yes')
            ->assertExitCode(0);
    }
}
