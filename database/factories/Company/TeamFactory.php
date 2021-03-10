<?php

namespace Database\Factories\Company;

use App\Models\Company\Team;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

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
        ];
    }
}
