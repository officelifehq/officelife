<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'job_opening_id' => function (array $attributes) {
                return JobOpening::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'employee_id' => function (array $attributes) {
                return Employee::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'uuid' => (int) $this->faker->uuid,
            'desired_salary' => $this->faker->numberBetween(1, 100000),
            'notes' => $this->faker->text(100),
            'url' => $this->faker->url(),
        ];
    }
}
