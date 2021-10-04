<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\IssueType;
use Illuminate\Database\Eloquent\Factories\Factory;

class IssueTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IssueType::class;

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
            'icon_hex_color' => $this->faker->hexColor(),
        ];
    }
}
