<?php

namespace Database\Factories\Company;

use App\Models\Company\OneOnOneEntry;
use App\Models\Company\OneOnOneTalkingPoint;
use Illuminate\Database\Eloquent\Factories\Factory;

class OneOnOneTalkingPointFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OneOnOneTalkingPoint::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'one_on_one_entry_id' => OneOnOneEntry::factory(),
            'description' => $this->faker->sentence(),
            'checked' => false,
        ];
    }
}
