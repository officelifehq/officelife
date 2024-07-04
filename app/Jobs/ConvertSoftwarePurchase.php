<?php

namespace App\Jobs;

use App\Models\Company\Software;
use App\Services\Company\Adminland\Expense\ConvertAmountFromOneCurrencyToCompanyCurrency;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConvertSoftwarePurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Software instance.
     */
    public Software $software;

    /**
     * Create a new job instance.
     */
    public function __construct(Software $software)
    {
        $this->software = $software;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $array = (new ConvertAmountFromOneCurrencyToCompanyCurrency)->execute(
            amount: $this->software->purchase_amount,
            amountCurrency: $this->software->currency,
            companyCurrency: $this->software->company->currency,
            amountDate: $this->software->purchased_at ? $this->software->purchased_at : Carbon::now(),
        );

        if (is_null($array)) {
            return;
        }

        Software::where('id', $this->software->id)->update([
            'exchange_rate' => $array['exchange_rate'],
            'converted_purchase_amount' => $array['converted_amount'],
            'converted_to_currency' => $array['converted_to_currency'],
            'converted_at' => $array['converted_at'],
        ]);
    }
}
