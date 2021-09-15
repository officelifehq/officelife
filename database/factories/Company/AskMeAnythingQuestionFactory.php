<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\AskMeAnythingSession;
use App\Models\Company\AskMeAnythingQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class AskMeAnythingQuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AskMeAnythingQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'ask_me_anything_session_id' => AskMeAnythingSession::factory(),
            'employee_id' => Employee::factory(),
            'question' => $this->faker->sentence,
            'answered' => false,
            'anonymous' => false,
        ];
    }
}
