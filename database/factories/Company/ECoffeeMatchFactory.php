<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\ECoffee;
use App\Models\Company\Employee;
use App\Models\Company\ECoffeeMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class ECoffeeMatchFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ECoffeeMatch::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $company = Company::factory()->create();

        return [
            'e_coffee_id' => ECoffee::factory()->create([
                'company_id' => $company->id,
            ]),
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'with_employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'happened' => true,
        ];
    }
}
