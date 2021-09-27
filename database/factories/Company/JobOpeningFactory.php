<?php

namespace Database\Factories\Company;

use Illuminate\Support\Str;
use App\Models\Company\Team;
use App\Models\Company\Company;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;
use App\Models\Company\RecruitingStageTemplate;
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
            'position_id' => function (array $attributes) {
                return Position::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'team_id' => function (array $attributes) {
                return Team::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'recruiting_stage_template_id' => function (array $attributes) {
                return RecruitingStageTemplate::factory()->create([
                    'company_id' => $attributes['company_id'],
                ]);
            },
            'active' => true,
            'fulfilled' => false,
            'reference_number' => $this->faker->text(10),
            'title' => $this->faker->text(100),
            'description' => $this->faker->text(300),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title'], '-');
            },
            'activated_at' => $this->faker->dateTimeThisCentury(),
        ];
    }
}
