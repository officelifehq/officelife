<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\BirthdayHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BirthdayHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_indicates_if_a_birthday_occurs_in_the_next_x_days(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // date is 2018-01-01
        $date = Carbon::now();
        $this->assertTrue(BirthdayHelper::isBirthdayInXDays(Carbon::now(), $date, 30));

        // date is 2018-01-20
        $date = Carbon::createFromFormat('Y-m-d', '2018-01-20');
        $this->assertTrue(BirthdayHelper::isBirthdayInXDays(Carbon::now(), $date, 30));

        // date is 2018-01-30
        $date = Carbon::createFromFormat('Y-m-d', '2018-01-30');
        $this->assertTrue(BirthdayHelper::isBirthdayInXDays(Carbon::now(), $date, 30));

        // date is 2018-02-20
        $date = Carbon::createFromFormat('Y-m-d', '2018-02-20');
        $this->assertFalse(BirthdayHelper::isBirthdayInXDays(Carbon::now(), $date, 30));

        // date is 1887-02-20
        $date = Carbon::createFromFormat('Y-m-d', '1887-02-20');
        $this->assertFalse(BirthdayHelper::isBirthdayInXDays(Carbon::now(), $date, 30));

        // date is 1887-02-20
        $date = Carbon::createFromFormat('Y-m-d', '1887-01-20');
        $this->assertTrue(BirthdayHelper::isBirthdayInXDays(Carbon::now(), $date, 30));
    }

    /** @test */
    public function it_indicates_if_a_birthday_occurs_in_a_range_of_dates(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        // date is 2018-01-01
        $date = Carbon::now();
        $min = Carbon::now()->subDays(3);
        $max = Carbon::now()->addDays(3);
        $this->assertTrue(BirthdayHelper::isBirthdayInRange($date, $min, $max));
        $this->assertFalse(BirthdayHelper::isBirthdayInRange($date, $min->addMonth(), $max->addMonth()));
    }
}
