<?php

namespace Tests\Unit\Services\Company\Adminland\Company;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\ExpenseCategory;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Company\ProvisionDefaultAccountData;

class ProvisionDefaultAccountDataTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_populates_default_data_in_the_account(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
        ];

        (new ProvisionDefaultAccountData)->execute($request);

        Carbon::now();
        $this->assertEquals(
            5,
            CompanyPTOPolicy::count()
        );
        $this->assertEquals(
            1828,
            CompanyCalendar::count()
        );
        $this->assertEquals(
            5,
            ExpenseCategory::count()
        );
    }
}
