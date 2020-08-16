<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Jobs\StopRateYourManagerProcess;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StopRateYourManagerProcessTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_stops_the_rate_your_manager_process(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // those two should be marked inactive
        factory(RateYourManagerSurvey::class, 2)->create([
            'active' => true,
            'valid_until_at' => '2017-01-01 00:00:00',
        ]);
        // this one should remain active
        factory(RateYourManagerSurvey::class)->create([
            'active' => true,
            'valid_until_at' => '2018-01-02 00:00:00',
        ]);
        // this one should be untouched
        factory(RateYourManagerSurvey::class)->create([
            'active' => false,
            'valid_until_at' => '2017-01-01 00:00:00',
        ]);

        $job = new StopRateYourManagerProcess();
        $job->dispatch();
        $job->handle();

        $this->assertEquals(
            1,
            DB::table('rate_your_manager_surveys')
                ->where('active', true)
                ->count()
        );
    }
}
