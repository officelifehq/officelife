<?php

namespace Tests\Unit\Services\Company\Employee\Holiday;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Company\Employee;
use Illuminate\Validation\ValidationException;
use App\Models\Company\EmployeeDailyCalendarEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Employee\Holiday\ProcessDailyTimeOffBalance;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class ProcessDailyTimeOffBalanceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_calculates_the_daily_time_off_balance(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = factory(Employee::class)->create([
            'holiday_balance' => 30,
        ]);

        // create a policy for this year
        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2018,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        (new CreateCompanyPTOPolicy)->execute($request);

        $request = [
            'employee_id' => $michael->id,
            'date' => '2018-10-10',
        ];

        $cron = (new ProcessDailyTimeOffBalance)->execute($request);

        $this->assertDatabaseHas('employee_daily_calendar_entries', [
            'id' => $cron->id,
            'employee_id' => $michael->id,
            'log_date' => '2018-10-10 00:00:00',
            'new_balance' => 30.115,
            'daily_accrued_amount' => 0.115,
            'current_holidays_per_year' => 30,
            'default_amount_of_allowed_holidays_in_company' => 1,
        ]);

        $this->assertInstanceOf(
            EmployeeDailyCalendarEntry::class,
            $cron
        );

        // case of a day that was not worked
        // the new balance should not changed
        $request = [
            'employee_id' => $michael->id,
            'date' => '2018-11-04',
        ];

        $cron = (new ProcessDailyTimeOffBalance)->execute($request);

        $this->assertDatabaseHas('employee_daily_calendar_entries', [
            'id' => $cron->id,
            'employee_id' => $michael->id,
            'log_date' => '2018-11-04 00:00:00',
            'new_balance' => 30.115,
            'daily_accrued_amount' => 0.115,
            'current_holidays_per_year' => 30,
            'default_amount_of_allowed_holidays_in_company' => 1,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new ProcessDailyTimeOffBalance)->execute($request);
    }
}
