<?php

namespace App\Jobs;

use App\Models\Company\Company;
use App\Services\Company\Employee\ECoffee\MatchEmployeesForECoffee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateNewECoffeeSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The company instance.
     */
    public Company $company;

    /**
     * Create a new job instance.
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Create a new session of ECoffee for the company.
     */
    public function handle(): void
    {
        (new MatchEmployeesForECoffee)->execute([
            'company_id' => $this->company->id,
        ]);
    }
}
