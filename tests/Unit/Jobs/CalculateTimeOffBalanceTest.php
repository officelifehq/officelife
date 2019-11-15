<?php

namespace Tests\Unit\Jobs;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use App\Jobs\CalculateTimeOffBalance;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class CalculateTimeOffBalanceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_triggers_the_service(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = factory(Employee::class)->create([
            'holiday_balance' => 30,
        ]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        $date = Carbon::create(2018, 10, 10, 7, 0, 0);

        CalculateTimeOffBalance::dispatch($michael, $date->format('Y-m-d'));

        $this->assertDatabaseHas('employee_daily_calendar_entries', [
            'employee_id' => $michael->id,
            'log_date' => '2018-10-10',
            'new_balance' => 30.115,
            'daily_accrued_amount' => 0.115,
            'current_holidays_per_year' => 30,
            'default_amount_of_allowed_holidays_in_company' => 1,
        ]);
    }
}
