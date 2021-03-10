<?php

namespace Database\Factories\Company;

use App\Models\Company\Flow;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class FlowFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Flow::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => 'Birthdate',
            'type' => 'employee_joins_company',
        ];
    }
}
