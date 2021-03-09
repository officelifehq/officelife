<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Hardware;
use Illuminate\Database\Eloquent\Factories\Factory;

class HardwareFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hardware::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'name' => $this->faker->title,
            'serial_number' => $this->faker->title,
        ];
    }
}
