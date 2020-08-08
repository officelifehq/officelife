<?php

namespace Tests\Unit\Services\Company\Adminland\Expense;

use Exception;
use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use GuzzleHttp\Handler\MockHandler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\Expense\ConvertAmountFromOneCurrencyToCompanyCurrency;

class ConvertAmountFromOneCurrencyToCompanyCurrencyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_converts_an_amount_from_eur_to_cad_and_store_rate_in_cache(): void
    {
        config(['officelife.currency_layer_api_key' => 'test']);
        config(['officelife.currency_layer_url' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $company = factory(Company::class)->create([
            'currency' => 'USD',
        ]);
        $employee = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        $expense = factory(Expense::class)->create([
            'employee_id' => $employee->id,
            'currency' => 'EUR',
            'amount' => '10000',
            'expensed_at' => '2020-07-20',
        ]);

        $this->assertFalse(
            Cache::has('exchange-rate-usd-eur-2020-07-20')
        );

        $expense = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute($expense, $client);

        $this->assertTrue(
            Cache::has('exchange-rate-usd-eur-2020-07-20')
        );

        $this->assertInstanceOf(
            Expense::class,
            $expense
        );

        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'exchange_rate' => 0.847968,
            'converted_amount' => 11792.897845202,
            'converted_to_currency' => 'USD',
        ]);
    }

    /** @test */
    public function it_does_nothing_if_company_currency_is_the_same_as_expense_currency(): void
    {
        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $company = factory(Company::class)->create([
            'currency' => 'USD',
        ]);
        $employee = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        $expense = factory(Expense::class)->create([
            'employee_id' => $employee->id,
            'currency' => 'USD',
            'amount' => '10000',
            'expensed_at' => '2020-07-20',
        ]);

        $expense = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute($expense, $client);

        $this->assertNull(
            $expense
        );

        $this->assertDatabaseHas('expenses', [
            'exchange_rate' => null,
            'converted_amount' => null,
            'converted_to_currency' => null,
        ]);
    }

    /** @test */
    public function it_raises_an_exception_if_it_cant_access_currency_layer(): void
    {
        config(['officelife.currency_layer_api_key' => 'test']);

        $body = file_get_contents(base_path('tests/Fixtures/Services/Adminland/Expense/ConvertAmountFromOneCurrencyToCompanyCurrencyResponse.json'));
        $mock = new MockHandler([new Response(200, [], $body)]);
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);

        $company = factory(Company::class)->create([
            'currency' => 'CAD',
        ]);
        $employee = factory(Employee::class)->create([
            'company_id' => $company->id,
        ]);
        $expense = factory(Expense::class)->create([
            'employee_id' => $employee->id,
            'currency' => 'EUR',
            'amount' => '10000',
            'expensed_at' => '2020-07-20',
        ]);

        $this->expectException(Exception::class);
        $expense = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute($expense, $client);
    }
}
