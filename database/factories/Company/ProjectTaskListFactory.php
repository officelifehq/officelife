<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectTaskList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectTaskListFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectTaskList::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $project = Project::factory()->create();

        return [
            'project_id' => $project->id,
            'author_id' => Employee::factory()->create([
                'company_id' => $project->company_id,
            ]),
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
        ];
    }
}
