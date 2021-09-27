<?php

namespace Tests\Unit\Jobs\Invoicing;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Company;
use App\Models\Company\CompanyDailyUsageHistory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Jobs\Invoicing\CreateMonthlyInvoiceForCompanies;

class CreateMonthlyInvoiceForCompaniesTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_the_monthly_invoice_for_a_company(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        config(['officelife.enable_paid_plan' => true]);
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

        CreateMonthlyInvoiceForCompanies::dispatch();

        $this->assertDatabaseHas('company_invoices', [
            'company_id' => $company->id,
            'usage_history_id' => $usageB->id,
        ]);

        $companyB = Company::factory()->create();
        CreateMonthlyInvoiceForCompanies::dispatch();

        $this->assertDatabaseMissing('company_invoices', [
            'company_id' => $companyB->id,
        ]);
    }
}
