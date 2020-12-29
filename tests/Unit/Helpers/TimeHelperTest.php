<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\TimeHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimeHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_number_of_hours_and_minutes(): void
    {
        $this->assertEquals(
            [
                'hours' => 1,
                'minutes' => 40,
            ],
            TimeHelper::convertToHoursAndMinutes(100)
        );

        $this->assertEquals(
            [
                'hours' => 3,
                'minutes' => 20,
            ],
            TimeHelper::convertToHoursAndMinutes(200)
        );

        $this->assertEquals(
            [
                'hours' => 0,
                'minutes' => 1,
            ],
            TimeHelper::convertToHoursAndMinutes(1)
        );
    }
}
