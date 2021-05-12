<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyInvoice;
use App\Models\Company\CompanyUsageHistory;
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
            'company_usage_history_id' => CompanyUsageHistory::factory(),
            'sent_to_payment_processor' => false,
            'receipt_sent_to_customer' => false,
            'email_address_invoice_sent_to' => $this->faker->email,
        ];
    }
}
