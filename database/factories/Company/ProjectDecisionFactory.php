<?php

namespace Database\Factories\Company;

use Carbon\Carbon;
use App\Models\Company\Company;
use App\Models\Company\Project;
use App\Models\Company\Employee;
use App\Models\Company\ProjectDecision;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectDecisionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProjectDecision::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'project_id' => Project::factory()->create([
                'company_id' => $company->id,
            ]),
            'author_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'title' => $this->faker->name,
            'decided_at' => Carbon::now(),
        ];
    }
}
