<?php

namespace Database\Factories\Company;

use App\Models\Company\Place;
use App\Models\Company\Country;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PlaceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Place::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'street' => $this->faker->streetName,
            'city' => $this->faker->city,
            'province' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'country_id' => Country::factory(),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'placable_id' => Employee::factory(),
            'placable_type' => 'App\Models\Company\Employee',
        ];
    }
}
