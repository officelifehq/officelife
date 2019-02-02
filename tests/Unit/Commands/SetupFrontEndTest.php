<?php

namespace Tests\Unit\Models\Account;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SetupFrontEndTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_setups_the_front_end_environnement()
    {
        $this->artisan('setup:frontendtesting')
            ->assertExitCode(0);
    }
}
