<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\HolidayHelper;
use App\Models\Company\Employee;
use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HolidayHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_number_of_holidays_earned_each_month(): void
    {
        $michael = factory(Employee::class)->create([
            'amount_of_allowed_holidays' => 30,
        ]);

        $this->assertEquals(
            2.5,
            HolidayHelper::getHolidaysEarnedEachMonth($michael)
        );
    }

    /** @test */
    public function it_returns_the_number_of_days_left_to_earn_this_the_end_of_the_year(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $policy = factory(CompanyPTOPolicy::class)->create([
            'year' => 2018,
        ]);
        $michael = factory(Employee::class)->create([
            'amount_of_allowed_holidays' => 30,
        ]);

        // build the calendar of days
        $date = Carbon::create($policy->year);
        for ($i = 1; $i <= DateHelper::daysInYear($date); $i++) {
            $isWorked = true;
            if ($date->isSaturday() || $date->isSunday()) {
                $isWorked = false;
            }

            factory(CompanyCalendar::class)->create([
                'company_pto_policy_id' => $policy->id,
                'day' => $date->format('Y-m-d'),
                'is_worked' => $isWorked,
            ]);
            $date->addDay();
        }

        Carbon::setTestNow(Carbon::create(2018, 10, 1));
        $this->assertEquals(
            7.8,
            HolidayHelper::getNumberOfDaysLeftToEarn($policy, $michael)
        );
    }

    /** @test */
    public function it_returns_the_number_of_holidays_earned_each_day(): void
    {
        $michael = factory(Employee::class)->create([
            'amount_of_allowed_holidays' => 30,
        ]);

        $policy = factory(CompanyPTOPolicy::class)->create([
            'year' => 2018,
        ]);

        $this->assertEquals(
            0.12,
            HolidayHelper::getHolidaysEarnedEachDay($policy, $michael)
        );
    }

    /** @test */
    public function it_checks_if_a_day_is_worked_in_the_company(): void
    {
        $policy = factory(CompanyPTOPolicy::class)->create([
            'year' => 2018,
        ]);

        factory(CompanyCalendar::class)->create([
            'company_pto_policy_id' => $policy->id,
            'day' => '2018-10-01',
            'is_worked' => true,
        ]);

        $date = Carbon::createFromFormat('Y-m-d', '2018-10-01');

        $this->assertTrue(
            HolidayHelper::isDayWorkedForCompany($policy, $date)
        );
    }

    /** @test */
    public function it_checks_if_a_day_is_not_worked_in_the_company(): void
    {
        $policy = factory(CompanyPTOPolicy::class)->create([
            'year' => 2018,
        ]);

        factory(CompanyCalendar::class)->create([
            'company_pto_policy_id' => $policy->id,
            'day' => '2018-10-01',
            'is_worked' => false,
        ]);

        $date = Carbon::createFromFormat('Y-m-d', '2018-10-01');

        $this->assertFalse(
            HolidayHelper::isDayWorkedForCompany($policy, $date)
        );
    }
}
