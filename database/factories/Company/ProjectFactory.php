<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => 'API v3',
            'code' => '123456',
            'description' => 'it is going well',
            'status' => Project::CREATED,
        ];
    }
}
