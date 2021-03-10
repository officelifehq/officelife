<?php

namespace Database\Factories\Company;

use App\Models\Company\Morale;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoraleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Morale::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'emotion' => 1,
            'comment' => $this->faker->title,
        ];
    }
}
