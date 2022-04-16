<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectSprint;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectSprintFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectSprint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'project_board_id' => ProjectBoard::factory(),
            'name' => $this->faker->name,
            'is_board_backlog' => false,
            'active' => true,
        ];
    }
}
