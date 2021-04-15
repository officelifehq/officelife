<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\CompanyPTOPolicy\CreateCompanyPTOPolicy;

class DateHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_date_with_english_locale(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 01, 1978',
            DateHelper::formatDate($date)
        );
    }

    /** @test */
    public function it_gets_the_date_with_english_locale_with_a_timezone(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 02, 1978',
            DateHelper::formatDate($date, 'Australia/Perth')
        );
    }

    /** @test */
    public function it_gets_the_short_date_with_time_in_english_locale(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 01, 1978 17:56',
            DateHelper::formatShortDateWithTime($date)
        );
    }

    /** @test */
    public function it_gets_the_short_date_with_time_in_english_locale_with_a_timezone(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 02, 1978 01:56',
            DateHelper::formatShortDateWithTime($date, 'Australia/Perth')
        );
    }

    /** @test */
    public function it_gets_the_long_date_with_day_and_month(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'October 1st',
            DateHelper::formatMonthAndDay($date)
        );
    }

    /** @test */
    public function it_gets_the_day_and_the_month_in_parenthesis(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Sunday (Oct 1st)',
            DateHelper::formatDayAndMonthInParenthesis($date)
        );
    }

    /** @test */
    public function it_gets_the_day_and_the_month_in_parenthesis_with_a_timezone(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Monday (Oct 2nd)',
            DateHelper::formatDayAndMonthInParenthesis($date, 'Australia/Perth')
        );
    }

    /** @test */
    public function it_gets_a_short_date(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 01',
            DateHelper::formatShortMonthAndDay($date)
        );
    }

    /** @test */
    public function it_gets_the_complete_date(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Sunday, Oct 1st 1978',
            DateHelper::formatFullDate($date)
        );
    }

    /** @test */
    public function it_gets_the_month_as_a_string_and_translated(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'October',
            DateHelper::translateMonth($date)
        );
    }

    /** @test */
    public function it_gets_a_day(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Sunday',
            DateHelper::day($date)
        );
    }

    /** @test */
    public function it_gets_a_day_with_a_short_month(): void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 1st',
            DateHelper::dayWithShortMonth($date)
        );
    }

    /** @test */
    public function it_gets_the_next_occurence_of_a_date(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $date = Carbon::createFromFormat('Y-m-d', '1978-10-01');
        $this->assertEquals(
            '2018-10-01',
            DateHelper::getNextOccurence($date)->format('Y-m-d')
        );

        $date = Carbon::createFromFormat('Y-m-d', '2000-10-01');
        $this->assertEquals(
            '2018-10-01',
            DateHelper::getNextOccurence($date)->format('Y-m-d')
        );
    }

    /** @test */
    public function it_returns_the_number_of_days_in_a_year(): void
    {
        $date = Carbon::createFromFormat('Y-m-d', '2019-10-01');
        $this->assertEquals(
            365,
            DateHelper::getNumberOfDaysInYear($date)
        );

        $date = Carbon::createFromFormat('Y-m-d', '2020-10-01');
        $this->assertEquals(
            366,
            DateHelper::getNumberOfDaysInYear($date)
        );
    }

    /** @test */
    public function it_checks_if_the_date_is_in_the_past_the_present_or_the_future(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $date = Carbon::createFromFormat('Y-m-d', '2019-10-01');
        $this->assertEquals(
            'future',
            DateHelper::determineDateStatus($date)
        );

        $date = Carbon::createFromFormat('Y-m-d', '2017-10-01');
        $this->assertEquals(
            'past',
            DateHelper::determineDateStatus($date)
        );

        $date = Carbon::createFromFormat('Y-m-d', '2018-01-01');
        $this->assertEquals(
            'current',
            DateHelper::determineDateStatus($date)
        );
    }

    /** @test */
    public function it_returns_the_number_of_hours_or_days_left(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1, 10, 50));
        $date = Carbon::createFromFormat('Y-m-d', '2018-01-03');
        $this->assertEquals(
            '2 days left',
            DateHelper::hoursOrDaysLeft($date)
        );

        $date = Carbon::create(2018, 1, 2, 8, 50);
        $this->assertEquals(
            '22 hours left',
            DateHelper::hoursOrDaysLeft($date)
        );

        $date = Carbon::create(2018, 1, 1, 9, 50);
        $this->assertEquals(
            '1 hour left',
            DateHelper::hoursOrDaysLeft($date)
        );
    }

    /** @test */
    public function it_generates_a_calendar(): void
    {
        $michael = Employee::factory()->asAdministrator()->create([]);

        $request = [
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'year' => 2020,
            'default_amount_of_allowed_holidays' => 1,
            'default_amount_of_sick_days' => 1,
            'default_amount_of_pto_days' => 1,
        ];

        $ptoPolicy = (new CreateCompanyPTOPolicy)->execute($request);

        $calendar = DateHelper::prepareCalendar($ptoPolicy);

        $this->assertEquals(
            12,
            count($calendar)
        );

        $this->assertEquals(
            30,
            count($calendar[1])
        );

        $this->assertEquals(
            'Jan',
            $calendar[0][0]['abbreviation']
        );

        $this->assertEquals(
            3,
            $calendar[0][1]['day_of_week']
        );

        $this->assertEquals(
            'W',
            $calendar[0][1]['abbreviation']
        );
    }
}
