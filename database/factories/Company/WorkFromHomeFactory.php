<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\WorkFromHome;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkFromHomeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WorkFromHome::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'date' => '2010-01-01',
            'work_from_home' => true,
        ];
    }
}
