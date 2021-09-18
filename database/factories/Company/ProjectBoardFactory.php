<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\ProjectBoard;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectBoardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectBoard::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'name' => $this->faker->name,
        ];
    }
}
