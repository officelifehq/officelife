<?php

namespace Tests\Unit\Helpers;

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
            425,
            count($array)
        );

        $this->assertEquals(
            [
                'value' => 'Africa/Banjul',
                'label' => '(UTC +00:00) Africa/Banjul',
            ],
            $array[3]
        );

        $this->assertEquals(
            [
                'value' => 'Antarctica/Rothera',
                'label' => '(UTC -03:00) Rothera (Antarctica/Rothera)',
            ],
            $array[300]
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
