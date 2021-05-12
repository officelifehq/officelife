<?php

namespace App\Jobs\Invoicing;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use App\Models\Company\Company;
use App\Models\Company\CompanyInvoice;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Company\CompanyUsageHistory;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateMonthlyInvoiceForCompany implements ShouldQueue
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
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
    }

    /**
     * Create the monthly invoice for the company, based on the usage in the
     * account.
     */
    public function handle(): void
    {
        $usage = CompanyUsageHistory::where('company_id', $this->company->id)
            ->whereBetween('created_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
            ->orderBy('number_of_active_employees', 'desc')
            ->first();

        if (! $usage) {
            return;
        }

        CompanyInvoice::create([
            'company_id' => $this->company->id,
            'company_usage_history_id' => $usage->id,
        ]);
    }
}
