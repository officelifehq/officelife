<?php

namespace App\Helpers;

use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

class MoneyHelper
{
    /**
     * Formats the money to get the correct display depending on the currency.
     *
     * @param integer $amount
     * @param string $currency
     * @param string $locale
     * @return string|null
     */
    public static function format(int $amount, string $currency, string $locale = 'en_US'): ?string
    {
        $money = new Money($amount, new Currency($currency));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}
