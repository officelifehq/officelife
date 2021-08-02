<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'slug' => 'company-name',
            'currency' => 'USD',
            'code_to_join_company' => 'USD',
        ];
    }
}
