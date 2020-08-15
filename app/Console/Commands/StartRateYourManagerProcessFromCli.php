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
        StartRateYourManagerProcess::dispatch();
    }
}
