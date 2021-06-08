<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Software;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoftwareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Software::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->name(),
            'website' => $this->faker->url,
            'product_key' => $this->faker->ipv6,
            'seats' => $this->faker->numberBetween(4, 10),
            'licensed_to_name' => $this->faker->name(),
            'licensed_to_email_address' => $this->faker->email,
            'order_number' => $this->faker->randomNumber(),
            'purchase_amount' => $this->faker->randomNumber(),
            'currency' => $this->faker->currencyCode,
            'purchased_at' => $this->faker->dateTimeThisCentury(),
        ];
    }
}
