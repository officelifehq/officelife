<?php

namespace Tests\Unit\Commands;

use App\Jobs\StopRateYourManagerProcess;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class StopRateYourManagerProcessFromCliTest extends TestCase
{
    /** @test */
    public function it_stops_the_rate_your_manager_process_from_the_cli(): void
    {
        Queue::fake();

        Artisan::call('rateyourmanagerprocess:stop');

        Queue::assertPushed(StopRateYourManagerProcess::class);
    }
}
