<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\StopRateYourManagerProcess;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StopRateYourManagerProcessFromCliTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_stops_the_rate_your_manager_process_from_the_cli(): void
    {
        Queue::fake();

        Artisan::call('rateyourmanagerprocess:stop');

        Queue::assertPushed(StopRateYourManagerProcess::class);
    }
}
