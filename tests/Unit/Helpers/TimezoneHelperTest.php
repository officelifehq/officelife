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
        $collection = TimezoneHelper::getListOfTimezones();

        $this->assertEquals(
            426,
            $collection->count()
        );

        $this->assertEquals(
            [
                'value' => 3,
                'label' => '(UTC +01:00) Africa/Algiers',
            ],
            $collection->toArray()[3]
        );

        $this->assertEquals(
            [
                'value' => 300,
                'label' => '(UTC +00:00) Atlantic/Reykjavik',
            ],
            $collection->toArray()[300]
        );
    }
}
