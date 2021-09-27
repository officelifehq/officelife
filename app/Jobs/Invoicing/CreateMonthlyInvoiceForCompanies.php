<?php

namespace App\Jobs\Invoicing;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use App\Models\Company\CompanyInvoice;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Company\CompanyDailyUsageHistory;

class CreateMonthlyInvoiceForCompanies implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create the monthly invoice for the company, based on the usage in the
     * account.
     */
    public function handle(): void
    {
        if (! config('officelife.enable_paid_plan')) {
            return;
        }

        Company::chunk(100, function ($companies) {
            foreach ($companies as $company) {
                $usage = CompanyDailyUsageHistory::where('company_id', $company->id)
                    ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                    ->orderBy('number_of_active_employees', 'desc')
                    ->first();

                if (! $usage) {
                    continue;
                }

                CompanyInvoice::create([
                    'company_id' => $company->id,
                    'usage_history_id' => $usage->id,
                ]);
            }
        });
    }
}
