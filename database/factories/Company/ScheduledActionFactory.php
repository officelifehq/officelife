<?php

namespace Database\Factories\Company;

use App\Models\Company\Action;
use App\Models\Company\Employee;
use App\Models\Company\ScheduledAction;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduledActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ScheduledAction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'action_id' => Action::factory(),
            'employee_id' => Employee::factory(),
            'triggered_at' => $this->faker->dateTimeThisCentury(),
            'content' => json_encode(['task_name' => 'Create a new project']),
            'processed' => false,
        ];
    }
}
