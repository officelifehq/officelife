<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\RateYourManagerAnswer;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateYourManagerAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RateYourManagerAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'rate_your_manager_survey_id' => RateYourManagerSurvey::factory(),
            'employee_id' => Employee::factory(),
            'rating' => RateYourManagerAnswer::BAD,
            'comment' => 'A really bad manager',
        ];
    }
}
