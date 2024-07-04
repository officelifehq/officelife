<?php

namespace App\Jobs;

use App\Models\Company\DirectReport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class StartRateYourManagerProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Start the Rate your Manager monthly process.
     * This will ask every employee to rate their manager(s).
     */
    public function handle(): void
    {
        $managers = DirectReport::get()->unique('manager_id');

        foreach ($managers as $manager) {
            AskEmployeesToRateTheirManager::dispatch($manager->manager)->onQueue('low');
        }
    }
}
