<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\Company\ScheduledAction;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcessAllScheduledActions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle()
    {
        $actions = ScheduledAction::whereDate('triggered_at', Carbon::today())
            ->where('processed', false)
            ->get();

        foreach ($actions as $action) {
            ProcessScheduleAction::dispatch($action);
        }
    }
}
