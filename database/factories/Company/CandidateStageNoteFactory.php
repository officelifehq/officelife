<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\CandidateStage;
use App\Models\Company\CandidateStageNote;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateStageNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CandidateStageNote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'candidate_stage_id' => CandidateStage::factory(),
            'author_id' => Employee::factory(),
            'note' => $this->faker->realText(),
            'author_name' => $this->faker->name,
        ];
    }
}
