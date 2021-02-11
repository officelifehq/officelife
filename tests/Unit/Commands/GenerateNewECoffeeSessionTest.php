<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use App\Models\Company\Company;
use App\Jobs\CreateNewECoffeeSession;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GenerateNewECoffeeSessionTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_starts_the_generate_new_e_coffee_sessions(): void
    {
        Queue::fake();

        Company::factory()->create();

        Artisan::call('ecoffee:start');

        Queue::assertPushed(CreateNewECoffeeSession::class);
    }
}
