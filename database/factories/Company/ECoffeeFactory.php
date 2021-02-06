<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use Illuminate\Database\Eloquent\Factories\Factory;

class ECoffeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ECoffee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'batch_number' => 1,
            'active' => false,
        ];
    }
}
