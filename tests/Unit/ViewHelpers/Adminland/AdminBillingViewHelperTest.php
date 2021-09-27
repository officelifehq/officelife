<?php

namespace Tests\Unit\ViewHelpers\Adminland;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\CompanyInvoice;
use App\Models\Company\CompanyDailyUsageHistory;
use App\Models\Company\CompanyUsageHistoryDetails;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Adminland\AdminBillingViewHelper;

class AdminBillingViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_list_of_months(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $invoice = CompanyInvoice::factory()->create([
            'company_id' => $michael->company->id,
            'created_at' => '2018-01-01',
        ]);

        $collection = AdminBillingViewHelper::index($michael->company);

        $this->assertEquals(
            1,
            $collection->count()
        );

        $this->assertEquals(
            [
                'id' => $invoice->id,
                'number_of_active_employees' => 3,
                'month' => 'Jan 2018',
                'url' => env('APP_URL').'/'.$michael->company_id.'/account/billing/'.$invoice->id,
            ],
            $collection[0]
        );
    }

    /** @test */
    public function it_gets_the_detail_of_an_invoice(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $invoice = CompanyInvoice::factory()->create([
            'company_id' => $michael->company->id,
            'created_at' => '2018-01-01',
        ]);

        $history = CompanyDailyUsageHistory::factory()->create([
            'company_id' => $michael->company->id,
        ]);
        CompanyUsageHistoryDetails::factory()->create([
            'usage_history_id' => $history->id,
            'employee_name' => $michael->name,
            'employee_email' => $michael->email,
        ]);

        $array = AdminBillingViewHelper::show($invoice);

        $this->assertEquals(
            4,
            count($array)
        );

        $this->assertEquals(
            'Jan 2018',
            $array['month']
        );

        $this->assertEquals(
            'Jan 01, 2018',
            $array['day_with_max_employees']
        );
        $this->assertEquals(
            3,
            $array['number_of_active_employees']
        );
    }
}
