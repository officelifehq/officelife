<?php

namespace Database\Factories\Company;

use Carbon\Carbon;
use App\Models\Company\Project;
use App\Models\Company\ProjectTask;
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
        return [
            'project_id' => Project::factory(),
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
