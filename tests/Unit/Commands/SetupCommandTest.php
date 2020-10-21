<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use App\Console\Commands\Helpers\Command;

class SetupCommandTest extends TestCase
{
    /** @test */
    public function it_run_setup_command(): void
    {
        /** @var \Tests\Helpers\CommandCallerFake */
        $fake = Command::fake();

        Artisan::call('setup');

        $this->assertCount(5, $fake->buffer);
        $fake->assertContainsMessage('✓ Resetting application cache');
        $fake->assertContainsMessage('✓ Clear config cache');
        $fake->assertContainsMessage('✓ Clear route cache');
        $fake->assertContainsMessage('✓ Clear view cache');
        $fake->assertContainsMessage('✓ Performing migrations');
    }
}
