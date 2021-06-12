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

    /** @test */
    public function it_calculates_the_age_prior_to_the_birthday(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 1, 1));

        $date = Carbon::create(1990, 5, 1);

        $this->assertEquals(29, BirthdayHelper::age($date));
        $this->assertEquals(29, BirthdayHelper::age($date, 'UTC'));
        $this->assertEquals(29, BirthdayHelper::age($date, 'Europe/Paris'));
        $this->assertEquals(29, BirthdayHelper::age($date, 'America/Chicago'));
    }

    /** @test */
    public function it_calculates_the_age_on_birthday_for_local_time(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 4, 30, 23, 0, 0));

        $date = Carbon::create(1990, 5, 1);

        $this->assertEquals(29, BirthdayHelper::age($date));
        $this->assertEquals(29, BirthdayHelper::age($date, 'UTC'));

        // It's already 2020-05-01 for Paris
        $this->assertEquals(30, BirthdayHelper::age($date, 'Europe/Paris'));

        // It's still 2020-04-30 for Chicago
        $this->assertEquals(29, BirthdayHelper::age($date, 'America/Chicago'));
    }

    /** @test */
    public function it_calculates_the_age_on_birthday(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 5, 1));

        $date = Carbon::create(1990, 5, 1);

        $this->assertEquals(30, BirthdayHelper::age($date));
        $this->assertEquals(30, BirthdayHelper::age($date, 'UTC'));
        $this->assertEquals(30, BirthdayHelper::age($date, 'Europe/Paris'));

        // It's still 2020-04-30 for Chicago
        $this->assertEquals(29, BirthdayHelper::age($date, 'America/Chicago'));
    }

    /** @test */
    public function it_calculates_the_age_on_birthday_later(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 5, 1, 21, 0, 0));

        $date = Carbon::create(1990, 5, 1);

        $this->assertEquals(30, BirthdayHelper::age($date));
        $this->assertEquals(30, BirthdayHelper::age($date, 'UTC'));
        $this->assertEquals(30, BirthdayHelper::age($date, 'Europe/Paris'));
        $this->assertEquals(30, BirthdayHelper::age($date, 'America/Chicago'));
    }

    /** @test */
    public function it_calculates_the_age_the_day_after(): void
    {
        Carbon::setTestNow(Carbon::create(2020, 5, 2));

        $date = Carbon::create(1990, 5, 1);

        $this->assertEquals(30, BirthdayHelper::age($date));
        $this->assertEquals(30, BirthdayHelper::age($date, 'UTC'));
        $this->assertEquals(30, BirthdayHelper::age($date, 'Europe/Paris'));
        $this->assertEquals(30, BirthdayHelper::age($date, 'America/Chicago'));
    }
}
