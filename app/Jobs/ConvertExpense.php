<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Expense;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Adminland\Expense\ConvertAmountFromOneCurrencyToCompanyCurrency;

class ConvertExpense implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The place instance.
     *
     * @var Expense
     */
    public Expense $expense;

    /**
     * The Guzzle client instance.
     *
     * @var GuzzleClient
     */
    public ?GuzzleClient $client;

    /**
     * Create a new job instance.
     *
     * @param Expense $expense
     * @param GuzzleClient $client -> used in unit test only
     */
    public function __construct(Expense $expense, GuzzleClient $client = null)
    {
        $this->expense = $expense;
        $this->client = $client;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: $this->expense->amount,
            amountCurrency: $this->expense->currency,
            companyCurrency: $this->expense->company->currency,
            amountDate: $this->expense->expensed_at,
            client: $this->client,
        );

        if (is_null($array)) {
            return;
        }

        Expense::where('id', $this->expense->id)->update([
            'exchange_rate' => $array['exchange_rate'],
            'converted_amount' => $array['converted_amount'],
            'converted_to_currency' => $array['converted_to_currency'],
            'converted_at' => $array['converted_at'],
        ]);
    }
}
