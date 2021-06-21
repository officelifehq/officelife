<?php

namespace Database\Factories\Company;

use App\Models\Company\Wiki;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class WikiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Wiki::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->text(100),
        ];
    }
}
