<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogCompaniesMorale implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Carbon $date;

    /**
     * Create a new job instance.
     *
     * @param Carbon $date
     */
    public function __construct(Carbon $date)
    {
        $this->date = $date;
    }

    /**
     * Log the average morale of each company on the instance, based on how each
     * employee's feeling on the given date.
     * This job is meant to be executed every day at 11pm (UTC).
     */
    public function handle(): void
    {
        Company::select('id')->chunk(100, function ($companies) {
            $companies->each(function (Company $company) {
                ProcessCompanyMorale::dispatch([
                        'company_id' => $company->id,
                        'date' => $this->date,
                    ])->onQueue('low');
            });
        });
    }
}
