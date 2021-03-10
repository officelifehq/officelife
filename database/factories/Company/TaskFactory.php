<?php

namespace Database\Factories\Company;

use App\Models\Company\Task;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'title' => $this->faker->sentence(),
        ];
    }
}
