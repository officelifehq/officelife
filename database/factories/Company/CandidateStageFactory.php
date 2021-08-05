<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\JobOpening;
use App\Models\Company\CandidateStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidateStage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'candidate_id' => function (array $attributes) {
                return Employee::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'decider_id' => function (array $attributes) {
                return Employee::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'job_opening_recruiting_stage_id' => function (array $attributes) {
                return JobOpening::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'uuid' => $this->faker->uuid,
            'desired_salary' => $this->faker->numberBetween(1, 100000),
            'notes' => $this->faker->text(100),
            'url' => $this->faker->url(),
        ];
    }
}
