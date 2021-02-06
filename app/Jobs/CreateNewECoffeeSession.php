<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Employee\ECoffee\MatchEmployeesForECoffee;

class CreateNewECoffeeSession implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The company instance.
     *
     * @var Company
     */
    public Company $company;

    /**
     * Create a new job instance.
     *
     * @param Company $company
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
