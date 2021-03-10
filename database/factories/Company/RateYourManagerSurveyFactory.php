<?php

namespace Database\Factories\Company;

use App\Models\Company\Employee;
use App\Models\Company\RateYourManagerSurvey;
use Illuminate\Database\Eloquent\Factories\Factory;

class RateYourManagerSurveyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RateYourManagerSurvey::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'manager_id' => Employee::factory(),
            'active' => false,
        ];
    }
}
