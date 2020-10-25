<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\StartRateYourManagerProcess;

class StartRateYourManagerProcessFromCli extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rateyourmanagerprocess:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the Rate Your Manager process from the CLI';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        StartRateYourManagerProcess::dispatch();
    }
}
