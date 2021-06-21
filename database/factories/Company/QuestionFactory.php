<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'title' => $this->faker->sentence(),
            'active' => false,
        ];
    }
}
