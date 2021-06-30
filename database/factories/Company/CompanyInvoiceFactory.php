<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyInvoice;
use App\Models\Company\CompanyDailyUsageHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyInvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyInvoice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'company_daily_usage_history_id' => CompanyDailyUsageHistory::factory(),
            'sent_to_customer' => false,
            'customer_has_paid' => false,
            'email_address_invoice_sent_to' => $this->faker->email,
        ];
    }
}
