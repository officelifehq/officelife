<?php

namespace Tests\Unit\Helpers;

use Tests\TestCase;
use App\Helpers\MoneyHelper;
use Illuminate\Support\Facades\App;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MoneyHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_amount_correctly_formatted_depending_on_the_currency(): void
    {
        $this->assertEquals(
            'CA$132.40',
            MoneyHelper::format(13240, 'CAD')
        );

        //NumberFormatter adds a non-breaking space to it's output (which makes sense for currency), more info here: https://www.php.net/manual/en/numberformatter.formatcurrency.php#118304
        $number = str_replace("\xc2\xa0", ' ', MoneyHelper::format(13240, 'EUR', 'fr_FR'));
        $this->assertEquals(
            '132,40 €',
            $number
        );
    }

    /** @test */
    public function it_returns_the_amount_with_the_currency_symbol_in_the_right_locale()
    {
        App::setLocale('fr');

        $this->assertEquals('500,00 €', MoneyHelper::format(50000, 'EUR'));
        $this->assertEquals('5 038,29 €', MoneyHelper::format(503829, 'EUR'));
    }

    /** @test */
    public function it_parse_an_input_value_eur()
    {
        $this->assertEquals(50000, MoneyHelper::parseInput('500.00', 'EUR'));
        $this->assertEquals(503829, MoneyHelper::parseInput('5038.29', 'EUR'));
    }

    /** @test */
    public function it_parse_an_input_value_yen()
    {
        $this->assertEquals(500, MoneyHelper::parseInput('500', 'JPY'));
        $this->assertEquals(5038, MoneyHelper::parseInput('5038', 'JPY'));
        $this->assertEquals(5038, MoneyHelper::parseInput('5038.00', 'JPY'));
    }
}
