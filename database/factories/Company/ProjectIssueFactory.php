<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\IssueType;
use App\Models\Company\ProjectBoard;
use App\Models\Company\ProjectIssue;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectIssueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectIssue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'project_id' => Project::factory(),
            'reporter_id' => Employee::factory(),
            'project_board_id' => ProjectBoard::factory(),
            'issue_type_id' => IssueType::factory(),
            'id_in_project' => 0,
            'slug' => 'awesome',
            'key' => $this->faker->slug(3),
            'title' => $this->faker->name,
            'description' => $this->faker->name,
            'story_points' => $this->faker->numberBetween(1, 10),
        ];
    }
}
