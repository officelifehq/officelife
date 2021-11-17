<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\DisciplineCase;
use App\Models\Company\DisciplineEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisciplineEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DisciplineEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'discipline_case_id' => DisciplineCase::factory(),
            'author_id' => Employee::factory(),
            'author_name' => $this->faker->name(),
            'happened_at' => $this->faker->dateTimeThisCentury(),
            'description' => $this->faker->text(),
        ];
    }
}
