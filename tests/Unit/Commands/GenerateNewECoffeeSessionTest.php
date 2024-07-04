<?php

namespace Tests\Unit\Commands;

use App\Jobs\CreateNewECoffeeSession;
use App\Models\Company\Company;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class GenerateNewECoffeeSessionTest extends TestCase
{
    /** @test */
    public function it_starts_the_generate_new_e_coffee_sessions(): void
    {
        Queue::fake();

        Company::factory()->create();

        Artisan::call('ecoffee:start');

        Queue::assertPushed(CreateNewECoffeeSession::class);
    }
}
