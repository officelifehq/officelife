<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\IssueType;
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
            'issue_type_id' => IssueType::factory(),
            'key' => $this->faker->name,
            'title' => $this->faker->name,
            'description' => $this->faker->name,
        ];
    }
}
