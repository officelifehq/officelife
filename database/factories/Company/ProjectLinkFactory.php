<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\ProjectLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectLink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'type' => 'slack',
            'label' => $this->faker->name,
            'url' => $this->faker->url,
        ];
    }
}
