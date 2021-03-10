<?php

namespace Database\Factories\Company;

use App\Models\Company\Flow;
use App\Models\Company\Step;
use Illuminate\Database\Eloquent\Factories\Factory;

class StepFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Step::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'flow_id' => Flow::factory(),
            'number' => 3,
            'unit_of_time' => 'days',
            'modifier' => 'after',
            'real_number_of_days' => 3,
        ];
    }
}
