<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\EmployeeLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'action' => 'account_created',
            'author_id' => Employee::factory(),
            'author_name' => $this->faker->name,
            'audited_at' => $this->faker->dateTimeThisCentury(),
            'objects' => '{"user": 1}',
        ];
    }
}
