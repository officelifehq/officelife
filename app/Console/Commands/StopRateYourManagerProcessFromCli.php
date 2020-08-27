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
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->option('force')) {
            StopRateYourManagerProcess::dispatch(true);
        } else {
            StopRateYourManagerProcess::dispatch(false);
        }
    }
}
