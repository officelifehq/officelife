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
            'usage_history_id' => function (array $attributes) {
                return CompanyDailyUsageHistory::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'sent_to_customer' => false,
            'customer_has_paid' => false,
            'email_address_invoice_sent_to' => $this->faker->email,
        ];
    }
}
