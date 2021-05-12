<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\CompanyInvoice;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyInvoiceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $invoice = CompanyInvoice::factory()->create([]);
        $this->assertTrue($invoice->company()->exists());
    }

    /** @test */
    public function it_has_one_company_usage_history(): void
    {
        $invoice = CompanyInvoice::factory()->create([]);

        $this->assertTrue($invoice->companyUsageHistory()->exists());
    }
}
