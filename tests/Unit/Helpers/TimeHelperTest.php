<?php

namespace Tests\Unit\Helpers;

use App\Helpers\TimeHelper;
use Tests\TestCase;

class TimeHelperTest extends TestCase
{
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
                'hours' => 03,
                'minutes' => 20,
            ],
            TimeHelper::convertToHoursAndMinutes(200)
        );

        $this->assertEquals(
            [
                'hours' => 00,
                'minutes' => 01,
            ],
            TimeHelper::convertToHoursAndMinutes(01)
        );
    }

    /** @test */
    public function it_gets_a_string_representing_a_given_duration(): void
    {
        $this->assertEquals(
            '01h40',
            TimeHelper::durationInHumanFormat(100)
        );

        $this->assertEquals(
            '0h00',
            TimeHelper::durationInHumanFormat(0)
        );
    }
}
