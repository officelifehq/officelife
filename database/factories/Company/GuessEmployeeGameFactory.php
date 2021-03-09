<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\GuessEmployeeGame;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuessEmployeeGameFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuessEmployeeGame::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_who_played_id' => Employee::factory(),
            'employee_to_find_id' => Employee::factory(),
            'first_other_employee_to_find_id' => Employee::factory(),
            'second_other_employee_to_find_id' => Employee::factory(),
        ];
    }
}
