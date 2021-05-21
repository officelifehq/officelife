<?php

namespace Database\Factories\Company;

use Carbon\Carbon;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\EmployeePositionHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeePositionHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeePositionHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'position_id' => Position::factory(),
            'started_at' => Carbon::now()->subMonths(2),
            'ended_at' => Carbon::now(),
        ];
    }
}
