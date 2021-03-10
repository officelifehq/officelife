<?php

namespace Database\Factories\Company;

use App\Models\Company\Task;
use App\Models\Company\Company;
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
            'employee_id' => Company::factory(),
            'title' => $this->faker->sentence(),
        ];
    }
}
