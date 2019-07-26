<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DateHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_short_date_with_time_in_english_locale() : void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'Oct 01, 1978 17:56',
            DateHelper::getShortDateWithTime($date)
        );
    }

    /** @test */
    public function it_gets_the_long_date_with_day_and_month() : void
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '1978-10-01 17:56:03');

        $this->assertEquals(
            'October 1st',
            DateHelper::getLongDayAndMonth($date)
        );
    }

    /** @test */
    public function it_gets_the_next_occurence_of_a_date() : void
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
}
