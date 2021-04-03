<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\EmployeeDailyCalendarEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeDailyCalendarEntryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeDailyCalendarEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'new_balance' => 10,
            'daily_accrued_amount' => 1,
            'current_holidays_per_year' => 100,
            'default_amount_of_allowed_holidays_in_company' => 100,
            'log_date' => '2010-01-01',
        ];
    }
}
