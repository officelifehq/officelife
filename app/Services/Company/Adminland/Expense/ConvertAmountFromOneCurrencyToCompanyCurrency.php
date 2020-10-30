<?php

namespace App\Services\Company\Adminland\Expense;

use Carbon\Carbon;
use ErrorException;
use Money\Currency;
use Illuminate\Support\Str;
use App\Services\BaseService;
use App\Models\Company\Expense;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use App\Exceptions\WrongCurrencyLayerApiKeyException;

class ConvertAmountFromOneCurrencyToCompanyCurrency extends BaseService
{
    private Expense $expense;

    private float $rate;

    private string $companyCurrency;

    private string $expenseCurrency;

    private ?GuzzleClient $client;

    private string $query;

    /**
     * Converts an expense's amount from one currency to another, at the rate
     * that was active on the date the expense was made.
     * When converting, we will keep track of the exchange rate on that day.
     * The exchange rate for this day is saved in cache for 1h.
     * This allows us to reduce the number of queries if multiple conversions
     * have to be made.
     */
    public function execute(Expense $expense, GuzzleClient $client = null): ?Expense
    {
        $this->expense = $expense;
        $this->client = $client;

        $this->setVariables();

        if (! $this->checkIfExpenseCurrencyAndCompanyCurrencyAreDifferent()) {
            return null;
        }

        $this->getConversionRate();

        $this->convert();

        return $this->expense;
    }

    private function checkIfExpenseCurrencyAndCompanyCurrencyAreDifferent(): bool
    {
        if ($this->companyCurrency == $this->expenseCurrency) {
            return false;
        }

        return true;
    }

    private function setVariables(): void
    {
        $this->companyCurrency = $this->expense->employee->company->currency;
        $this->expenseCurrency = $this->expense->currency;

        $this->buildQuery();
    }

    private function getConversionRate(): void
    {
        if (Cache::has($this->cachedKey())) {
            $this->rate = Cache::get($this->cachedKey());
            return;
        }

        // this is merely for the unit test.
        if (is_null($this->client)) {
            $this->client = new GuzzleClient();
        }

        try {
            $response = $this->client->request('GET', $this->query);
        } catch (ClientException $e) {
            throw new \Exception('Can’t access Currency Layer');
        }

        $response = json_decode($response->getBody());

        try {
            $currencies = $this->companyCurrency.$this->expenseCurrency;
            $this->rate = $response->quotes->{$currencies};
        } catch (ErrorException $e) {
            throw new \Exception('Can’t get the proper exchange rate');
        }

        Cache::put($this->cachedKey(), $this->rate, now()->addMinutes(60));
    }

    private function convert(): void
    {
        $convertedAmount = $this->expense->amount / $this->rate;

        $this->expense->exchange_rate = $this->rate;
        $this->expense->converted_amount = $convertedAmount;
        $this->expense->converted_to_currency = $this->companyCurrency;
        $this->expense->converted_at = Carbon::now();
        $this->expense->save();
    }

    private function buildQuery(): void
    {
        if (is_null(config('officelife.currency_layer_api_key'))) {
            throw new WrongCurrencyLayerApiKeyException();
        }

        if (config('officelife.currency_layer_plan') == 'free') {
            $uri = config('officelife.currency_layer_url_free_plan');
        } else {
            $uri = config('officelife.currency_layer_url_paid_plan');
        }

        $query = http_build_query([
            'access_key' => config('officelife.currency_layer_api_key'),
            'source' => $this->companyCurrency,
            'currencies' => $this->expenseCurrency,
            'date' => $this->expense->expensed_at->format('Y-m-d'),
        ]);

        $this->query = Str::finish($uri, '?').$query;
    }

    /**
     * Returns the name of the key that can be in the cache for the given
     * exchange rate.
     * Format is `exchange-rate-eur-usd-1990-01-01`.
     *
     * @return string
     */
    private function cachedKey(): string
    {
        return Str::lower('exchange-rate-'.$this->companyCurrency.'-'.$this->expenseCurrency.'-'.$this->expense->expensed_at->format('Y-m-d'));
    }
}
