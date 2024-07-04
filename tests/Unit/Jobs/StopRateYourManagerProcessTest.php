<?php

namespace Tests\Unit\Jobs;

use App\Jobs\StopRateYourManagerProcess;
use App\Models\Company\RateYourManagerSurvey;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class StopRateYourManagerProcessTest extends TestCase
{
    /** @test */
    public function it_stops_the_rate_your_manager_process(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // those two should be marked inactive
        RateYourManagerSurvey::factory()->count(2)->create([
            'active' => true,
            'valid_until_at' => '2017-01-01 00:00:00',
        ]);
        // this one should remain active
        RateYourManagerSurvey::factory()->create([
            'active' => true,
            'valid_until_at' => '2018-01-02 00:00:00',
        ]);
        // this one should be untouched
        RateYourManagerSurvey::factory()->create([
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
