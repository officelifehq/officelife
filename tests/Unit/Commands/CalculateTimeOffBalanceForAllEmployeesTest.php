<?php

namespace Tests\Unit\Commands;

use Tests\TestCase;
use RuntimeException;
use App\Models\Company\Employee;
use Illuminate\Support\Facades\Bus;
use App\Jobs\CalculateTimeOffBalance;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CalculateTimeOffBalanceForAllEmployeesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_triggers_the_job(): void
    {
        Bus::fake();
        factory(Employee::class)->create([]);

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
