<?php

namespace App\Jobs\Invoicing;

use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Company\CompanyUsageHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class LogDailyMaxNumberOfActiveEmployeesInCompanies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Record the number of active employee for all the companies in the
     * instance.
     */
    public function handle(): void
    {
        $companies = Company::addSelect([
                'max_employees' => Employee::selectRaw('count(*)')
                    ->whereColumn('company_id', 'companies.id')
                    ->whereColumn('locked', 0),
            ])
            ->get();

        foreach ($companies as $company) {
            CompanyUsageHistory::create([
                'company_id' => $company->id,
                'number_of_active_employees' => $company->max_employees,
            ]);
        }
    }
}
