<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Company\RateYourManagerSurvey;

class StopRateYourManagerProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected bool $force = false;

    /**
     * Create a new job instance.
     */
    public function __construct(bool $force = false)
    {
        $this->force = $force;
    }

    /**
     * Stop the Rate your Manager monthly process.
     * This will mark all active survey inactive.
     */
    public function handle(): void
    {
        if ($this->force) {
            RateYourManagerSurvey::where('active', true)
                ->chunk(200, function ($surveys) {
                    foreach ($surveys as $survey) {
                        $this->updateSurveys($survey);
                    }
                });
        } else {
            RateYourManagerSurvey::where('active', true)
                ->where('valid_until_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->chunk(200, function ($surveys) {
                    foreach ($surveys as $survey) {
                        $this->updateSurveys($survey);
                    }
                });
        }
    }

    private function updateSurveys(RateYourManagerSurvey $survey): void
    {
        DB::table('rate_your_manager_answers')
            ->where('rate_your_manager_survey_id', $survey->id)
            ->update(['active' => 0]);

        $survey->active = false;
        $survey->save();
    }
}
