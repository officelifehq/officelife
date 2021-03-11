<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\StopRateYourManagerProcess;

class StopRateYourManagerProcessFromCli extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rateyourmanagerprocess:stop {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stop the Rate Your Manager process from the CLI';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        StopRateYourManagerProcess::dispatch((bool) $this->option('force'));
    }
}
