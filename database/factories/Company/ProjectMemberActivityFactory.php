<?php

namespace Database\Factories\Company;

use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectMemberActivity;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectMemberActivityFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectMemberActivity::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'employee_id' => Employee::factory(),
            'project_id' => Project::factory(),
        ];
    }
}
