<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\CompanyNews;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyNewsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyNews::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'company_id' => $company->id,
            'author_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'author_name' => $this->faker->name,
            'title' => $this->faker->sentence(),
            'content' => $this->faker->sentence(),
        ];
    }
}
