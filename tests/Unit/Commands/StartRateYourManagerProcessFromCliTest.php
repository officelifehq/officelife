<?php

namespace Tests\Unit\Commands;

use App\Jobs\StartRateYourManagerProcess;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StartRateYourManagerProcessFromCliTest extends TestCase
{
    /** @test */
    public function it_starts_the_rate_your_manager_process_from_the_cli(): void
    {
        Queue::fake();

        Artisan::call('rateyourmanagerprocess:start');

        Queue::assertPushed(StartRateYourManagerProcess::class);
    }
}
