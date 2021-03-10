<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\EmployeePlannedHoliday;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeePlannedHolidayFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeePlannedHoliday::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'planned_date' => '2010-01-01',
            'full' => true,
            'actually_taken' => false,
            'type' => 'holiday',
        ];
    }
}
