<?php

namespace Database\Factories\Company;

use App\Models\Company\RecruitingStage;
use App\Models\Company\RecruitingStageTemplate;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecruitingStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RecruitingStage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'recruiting_stage_template_id' => RecruitingStageTemplate::factory(),
            'name' => $this->faker->title,
            'position' => 1,
        ];
    }
}
