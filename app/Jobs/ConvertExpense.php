<?php

namespace App\Jobs;

use App\Models\Company\Expense;
use App\Services\Company\Adminland\Expense\ConvertAmountFromOneCurrencyToCompanyCurrency;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertExpense implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The place instance.
     */
    public Expense $expense;

    /**
     * Create a new job instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
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
