<?php

namespace Tests\Unit\Jobs\Invoicing;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\CompanyDailyUsageHistory;
use App\Jobs\Invoicing\CreateMonthlyInvoiceForCompany;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateMonthlyInvoiceForCompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_the_monthly_invoice_for_a_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $company = Company::factory()->create();
        CompanyDailyUsageHistory::factory()->create([
            'company_id' => $company->id,
            'number_of_active_employees' => 3,
            'created_at' => '2018-01-01 00:00:00',
        ]);
        $usageB = CompanyDailyUsageHistory::factory()->create([
            'company_id' => $company->id,
            'number_of_active_employees' => 30,
            'created_at' => '2018-01-28 00:00:00',
        ]);
        CompanyDailyUsageHistory::factory()->create([
            'company_id' => $company->id,
            'number_of_active_employees' => 10,
            'created_at' => '2018-01-30 00:00:00',
        ]);

        CreateMonthlyInvoiceForCompany::dispatch($company);

        $this->assertDatabaseHas('company_invoices', [
            'company_id' => $company->id,
            'company_daily_usage_history_id' => $usageB->id,
        ]);

        $companyB = Company::factory()->create();
        CreateMonthlyInvoiceForCompany::dispatch($companyB);

        $this->assertDatabaseMissing('company_invoices', [
            'company_id' => $companyB->id,
        ]);
    }
}
