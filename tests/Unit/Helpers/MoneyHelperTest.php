<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\MoneyHelper;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoneyHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_amount_correctly_formatted_depending_on_the_currency(): void
    {
        $this->assertEquals(
            'CA$132.40',
            MoneyHelper::format('13240', 'CAD')
        );

        //NumberFormatter adds a non-breaking space to it's output (which makes sense for currency), more info here: https://www.php.net/manual/en/numberformatter.formatcurrency.php#118304
        $number = str_replace("\xc2\xa0", ' ', MoneyHelper::format('13240', 'EUR', 'fr_FR'));
        $this->assertEquals(
            '132,40 €',
            $number
        );
    }
}
