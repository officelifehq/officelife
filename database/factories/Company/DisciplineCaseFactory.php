<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisciplineCaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisciplineCase::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'employee_id' => Employee::factory(),
            'opened_by_employee_id' => Employee::factory(),
            'opened_by_employee_name' => $this->faker->name(),
            'active' => $this->faker->boolean(),
        ];
    }
}
