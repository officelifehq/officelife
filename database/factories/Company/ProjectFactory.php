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
            'name' => $this->faker->name,
            'code' => '123456',
            'short_code' => 'off',
            'description' => $this->faker->sentence(),
            'status' => Project::CREATED,
        ];
    }
}
