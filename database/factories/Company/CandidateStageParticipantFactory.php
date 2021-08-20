<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\CandidateStage;
use App\Models\Company\CandidateStageParticipant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateStageParticipantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidateStageParticipant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'candidate_stage_id' => CandidateStage::factory(),
            'participant_id' => Employee::factory(),
            'participant_name' => $this->faker->name(),
            'participated' => $this->faker->boolean(),
            'participated_at' => $this->faker->dateTimeBetween(),
        ];
    }
}
