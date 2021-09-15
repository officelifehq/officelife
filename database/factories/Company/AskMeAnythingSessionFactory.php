<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\AskMeAnythingSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AskMeAnythingSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AskMeAnythingSession::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'theme' => $this->faker->sentence,
            'happened_at' => $this->faker->dateTimeThisCentury(),
            'active' => false,
        ];
    }
}
