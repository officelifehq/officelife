<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Candidate::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'job_opening_id' => Company::factory(),
            'name' => $this->faker->name(),
            'email' => $this->faker->email(),
            'uuid' => $this->faker->uuid,
            'desired_salary' => $this->faker->numberBetween(1, 100000),
            'notes' => $this->faker->text(100),
            'url' => $this->faker->url(),
        ];
    }
}
