<?php

namespace Tests\Unit\Commands;

use App\Jobs\CalculateTimeOffBalance;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Bus;
use RuntimeException;
use Tests\TestCase;

class CalculateTimeOffBalanceForAllEmployeesTest extends TestCase
{
    /** @test */
    public function it_triggers_the_job(): void
    {
        Bus::fake();
        Employee::factory()->create([]);

        $this->artisan('timeoff:calculate 2019-02-02');
        Bus::assertDispatched(CalculateTimeOffBalance::class);
    }

    /** @test */
    public function it_missses_an_argument(): void
    {
        $this->expectException(RuntimeException::class);
        $this->artisan('timeoff:calculate');
    }
}
