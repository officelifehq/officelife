<?php

namespace Database\Factories\Company;

use App\Models\Company\JobOpening;
use App\Models\Company\JobOpeningStage;
use App\Models\Company\RecruitingStage;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobOpeningStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobOpeningStage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_opening_id' => JobOpening::factory(),
            'recruiting_stage_id' => RecruitingStage::factory(),
        ];
    }
}
