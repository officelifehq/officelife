<?php

namespace Tests\Unit\Helpers;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DateHelperTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetShortDateWithTimeWithEnglishLocale()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', '2017-01-22 17:56:03');

        $this->assertEquals(
            'Jan 22, 2017 17:56',
            DateHelper::getShortDateWithTime($date)
        );
    }
}
