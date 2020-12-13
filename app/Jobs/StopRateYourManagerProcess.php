<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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
            DB::table('rate_your_manager_surveys')
                ->where('active', 1)
                ->update(['active' => 0]);
        } else {
            DB::table('rate_your_manager_surveys')
                ->where('active', 1)
                ->where('valid_until_at', '<', Carbon::now()->format('Y-m-d H:i:s'))
                ->update(['active' => 0]);
        }
    }
}
