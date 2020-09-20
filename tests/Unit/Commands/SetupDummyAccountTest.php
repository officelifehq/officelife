<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetupDummyAccountTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_populates_the_account_with_dummy_data(): void
    {
        $this->artisan('setup:dummyaccount', ['--skip-refresh' => true])
            ->expectsQuestion('Are you sure you want to proceed? This will delete ALL data in your environment.', 'yes')
            ->assertExitCode(0);
    }
}
