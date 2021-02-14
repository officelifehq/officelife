<?php

namespace Database\Factories\Company;

use Carbon\Carbon;
use App\Models\Company\Project;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectTaskList;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectTaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectTask::class;

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
            'project_task_list_id' => ProjectTaskList::factory()->create([
                'project_id' => $project->id,
            ]),
            'title' => $this->faker->title,
            'description' => $this->faker->paragraph,
            'completed' => false,
            'completed_at' => null,
        ];
    }

    /**
     * Indicate that task is completed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function completed()
    {
        return $this->state(function (array $attributes) {
            return [
                'completed' => true,
                'completed_at' => Carbon::now(),
            ];
        });
    }
}
