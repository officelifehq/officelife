<?php

namespace Tests\Unit\Services\Company\Adminland\Expense;

use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Log\Events\MessageLogged;
use App\Exceptions\WrongCurrencyLayerApiKeyException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Expense\ConvertAmountFromOneCurrencyToCompanyCurrency;

class ConvertAmountFromOneCurrencyToCompanyCurrencyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_converts_an_amount_from_eur_to_cad_and_store_rate_in_cache(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        config(['officelife.currency_layer_api_key' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        Http::fake([
            'api.currencylayer.com/historical*' => Http::response($body, 200),
        ]);

        $this->assertFalse(
            Cache::has('exchange-rate-usd-eur-2020-07-20')
        );

        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'EUR',
            companyCurrency: 'USD',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
        );

        $this->assertTrue(
            Cache::has('exchange-rate-usd-eur-2020-07-20')
        );

        $this->assertEquals(
            [
                'exchange_rate' => 0.847968,
                'converted_amount' => 1179290,
                'converted_to_currency' => 'USD',
                'converted_at' => '2018-01-01 00:00:00',
            ],
            $array
        );
    }

    /** @test */
    public function it_does_nothing_if_company_currency_is_the_same_as_expense_currency(): void
    {
        Http::fake([
            '*' => Http::response('', 400),
        ]);

        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'USD',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
        );

        $this->assertNull(
            $array
        );
    }

    /** @test */
    public function it_returns_null_if_call_unsuccessful(): void
    {
        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/currencylayer_fail.json'));
        Http::fake([
            'api.currencylayer.com/historical*' => Http::response($body, 500),
        ]);

        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'EUR',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
        );

        $this->assertNull(
            $array
        );
    }

    /** @test */
    public function it_logs_the_exception_if_call_is_unsuccessful(): void
    {
        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/currencylayer_fail.json'));
        Http::fake([
            'api.currencylayer.com/historical*' => Http::response($body, 500),
        ]);

        $logs = collect();
        Log::listen(function (MessageLogged $message) use ($logs) {
            $logs->push($message);
        });

        (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'EUR',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
        );

        $this->assertCount(1, $logs);
    }

    /** @test */
    public function it_logs_the_exception_if_rate_is_empty(): void
    {
        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/currencylayer_fail.json'));
        Http::fake([
            'api.currencylayer.com/historical*' => Http::response($body, 200),
        ]);

        $logs = collect();
        Log::listen(function (MessageLogged $message) use ($logs) {
            $logs->push($message);
        });

        (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'EUR',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
        );

        $this->assertCount(1, $logs);
    }

    /** @test */
    public function it_raises_an_exception_if_env_variables_are_not_set(): void
    {
        config(['officelife.currency_layer_api_key' => null]);

        Http::fake([
            '*' => Http::response('', 400),
        ]);

        $this->expectException(WrongCurrencyLayerApiKeyException::class);
        (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'EUR',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
        );
    }
}
