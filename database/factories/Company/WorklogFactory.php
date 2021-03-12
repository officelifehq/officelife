<?php

namespace Database\Factories\Company;

use App\Models\Company\Worklog;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorklogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Worklog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'content' => $this->faker->sentence(),
        ];
    }
}
