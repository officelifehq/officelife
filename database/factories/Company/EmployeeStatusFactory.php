<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeStatusFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeStatus::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => 'Permanent',
            'type' => 'internal',
        ];
    }
}
