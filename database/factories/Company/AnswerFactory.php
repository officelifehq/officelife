<?php

namespace Database\Factories\Company;

use App\Models\Company\Answer;
use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Answer::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'question_id' => Question::factory()->create([
                'company_id' => $company->id,
            ]),
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'body' => $this->faker->sentence(),
        ];
    }
}
