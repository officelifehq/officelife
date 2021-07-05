<?php

namespace App\Jobs\Invoicing;

use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Company\CompanyDailyUsageHistory;
use App\Models\Company\CompanyUsageHistoryDetails;

class LogDailyMaxNumberOfActiveEmployeesInCompanies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Record the number of active employee for all the companies in the
     * instance.
     */
    public function handle(): void
    {
        if (! config('officelife.enable_paid_plan')) {
            return;
        }

        Company::addSelect([
            'max_employees' => Employee::selectRaw('count(*)')
                ->whereColumn('company_id', 'companies.id')
                ->notLocked(),
        ])
        ->chunk(100, function ($companies) {
            foreach ($companies as $company) {
                $usage = CompanyDailyUsageHistory::create([
                    'company_id' => $company->id,
                    'number_of_active_employees' => $company->max_employees,
                ]);

                Employee::where('company_id', $company->id)
                    ->notLocked()
                    ->chunk(100, function ($employees) use ($usage) {
                        foreach ($employees as $employee) {
                            CompanyUsageHistoryDetails::create([
                                'usage_history_id' => $usage->id,
                                'employee_name' => $employee->name,
                                'employee_email' => $employee->email,
                            ]);
                        }
                    });
            }
        });
    }
}
