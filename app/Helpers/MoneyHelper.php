<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class MoneyHelper
{
    /**
     * Formats the money to get the correct display depending on the currency.
     */
    public static function format(int $amount, string $currency, ?string $locale = null): ?string
    {
        $money = new Money($amount, new Currency($currency));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter($locale ?? App::getLocale(), \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * Parse a monetary exchange value as storable integer.
     * Currency is used to know the precision of this currency.
     *
     * @param  string  $exchange  Amount value in exchange format (ex: 1.00).
     * @return int amount as storable format (ex: 14500)
     */
    public static function parseInput(string $exchange, string $currency): int
    {
        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());
        $money = $moneyParser->parse($exchange, new Currency($currency));

        return (int) $money->getAmount();
    }
}
