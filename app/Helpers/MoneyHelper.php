<?php

namespace App\Helpers;

use Money\Money;
use Money\Currency;
use Illuminate\Support\Facades\App;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\IntlMoneyFormatter;

class MoneyHelper
{
    /**
     * Formats the money to get the correct display depending on the currency.
     *
     * @param integer $amount
     * @param string $currency
     * @param string|null $locale
     * @return string|null
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
     * @param string $exchange  Amount value in exchange format (ex: 1.00).
     * @param string $currency
     * @return int  amount as storable format (ex: 14500)
     */
    public static function parseInput(string $exchange, string $currency): int
    {
        $moneyParser = new DecimalMoneyParser(new ISOCurrencies());
        $money = $moneyParser->parse($exchange, new Currency($currency));

        return (int) $money->getAmount();
    }
}
