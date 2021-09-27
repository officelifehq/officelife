<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\Candidate;
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
            'candidate_id' => Candidate::factory(),
            'decider_id' => Employee::factory(),
            'stage_position' => 1,
            'stage_name' => 'name',
            'status' => CandidateStage::STATUS_PENDING,
            'decider_name' => $this->faker->name,
            'decided_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
