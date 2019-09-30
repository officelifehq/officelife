<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\HolidayHelper;
use App\Models\Company\Employee;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class HolidayHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_number_of_holidays_earned_each_month() : void
    {
        $michael = factory(Employee::class)->create([
            'amount_of_allowed_holidays' => 30,
        ]);

        $this->assertEquals(
            2.5,
            HolidayHelper::getHolidaysEarnedEachMonth($michael)
        );
    }
}
