<?php

namespace Database\Factories\Company;

use App\Models\Company\Ship;
use App\Models\Company\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ship::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'title' => $this->faker->title,
        ];
    }
}
