<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobOpeningFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobOpening::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->text(100),

            'company_id' => Company::factory(),
            'position_id' => function (array $attributes) {
                return Position::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'sponsored_by_employee_id' => function (array $attributes) {
                return Employee::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'active' => true,
            'fulfilled' => false,
            'reference_number' => $this->faker->numberBetween(1, 100),
            'title' => $this->faker->text(100),
            'description' => $this->faker->text(300),
            'activated_at' => $this->faker->dateTimeThisCentury(),
        ];
    }
}
