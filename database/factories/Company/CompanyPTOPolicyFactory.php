<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyPTOPolicyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyPTOPolicy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'year' => 2020,
            'total_worked_days' => 250,
            'default_amount_of_allowed_holidays' => 30,
            'default_amount_of_sick_days' => 3,
            'default_amount_of_pto_days' => 5,
        ];
    }
}
