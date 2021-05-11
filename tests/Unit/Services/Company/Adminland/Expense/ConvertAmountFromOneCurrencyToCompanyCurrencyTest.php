<?php

namespace Tests\Unit\Services\Company\Adminland\Expense;

use Carbon\Carbon;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Support\Facades\Cache;
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
        config(['officelife.currency_layer_url' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $this->assertFalse(
            Cache::has('exchange-rate-usd-eur-2020-07-20')
        );

        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'EUR',
            companyCurrency: 'USD',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
            client: $client
        );

        $this->assertTrue(
            Cache::has('exchange-rate-usd-eur-2020-07-20')
        );

        $this->assertEquals(
            [
                'exchange_rate' => 0.847968,
                'converted_amount' => 11792.897845201705,
                'converted_to_currency' => 'USD',
                'converted_at' => '2018-01-01 00:00:00',
            ],
            $array
        );
    }

    /** @test */
    public function it_does_nothing_if_company_currency_is_the_same_as_expense_currency(): void
    {
        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'USD',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
            client: $client
        );

        $this->assertNull(
            $array
        );
    }

    /** @test */
    public function it_raises_an_exception_if_env_variables_are_not_set(): void
    {
        config(['officelife.currency_layer_api_key' => null]);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $this->expectException(WrongCurrencyLayerApiKeyException::class);
        (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: 10000,
            amountCurrency: 'USD',
            companyCurrency: 'EUR',
            amountDate: Carbon::createFromFormat('Y-m-d', '2020-07-20'),
            client: $client
        );
    }
}
