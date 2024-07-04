<?php

namespace App\Console\Commands;

use App\Jobs\CreateNewECoffeeSession;
use App\Models\Company\Company;
use Illuminate\Console\Command;

class GenerateNewECoffeeSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ecoffee:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new eCoffee sessions for all companies in the instance';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Company::chunk(100, function ($companies) {
            $companies->each(function (Company $company) {
                CreateNewECoffeeSession::dispatch($company)->onQueue('low');
            });
        });
    }
}
