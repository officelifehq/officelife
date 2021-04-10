<?php

namespace Tests\Unit\Helpers;

use DateTimeZone;
use Tests\TestCase;
use App\Helpers\TimezoneHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TimezoneHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_a_list_of_timezones(): void
    {
        $array = TimezoneHelper::getListOfTimezones();

        $this->assertEquals(
            count(DateTimeZone::listIdentifiers()),
            count($array)
        );

        $this->assertArrayHasKey(
            'value',
            $array[0]
        );
        $this->assertArrayHasKey(
            'label',
            $array[0]
        );
    }

    /** @test */
    public function it_gets_the_timezone_as_an_array(): void
    {
        $this->assertEquals(
            [
                'value' => 'Africa/Banjul',
                'label' => '(UTC +00:00) Africa/Banjul',
            ],
            TimezoneHelper::getTimezoneKeyValue('Africa/Banjul')
        );
    }
}
