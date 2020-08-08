<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\Company\Expense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Company\Adminland\Expense\ConvertAmountFromOneCurrencyToCompanyCurrency as ExpenseConvertAmountFromOneCurrencyToCompanyCurrency;

class ConvertAmountFromOneCurrencyToCompanyCurrency implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The place instance.
     *
     * @var Expense
     */
    public Expense $expense;

    /**
     * Create a new job instance.
     *
     * @param Expense $expense
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        (new ExpenseConvertAmountFromOneCurrencyToCompanyCurrency)->execute($this->expense);
    }
}
