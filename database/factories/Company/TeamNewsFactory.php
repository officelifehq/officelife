<?php

namespace Database\Factories\Company;

use App\Models\Company\Team;
use App\Models\Company\Employee;
use App\Models\Company\TeamNews;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamNewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamNews::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'author_id' => Employee::factory(),
            'author_name' => $this->faker->name,
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentence(),
        ];
    }
}
