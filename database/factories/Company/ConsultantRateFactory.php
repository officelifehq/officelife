<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\ConsultantRate;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConsultantRateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ConsultantRate::class;

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
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'rate' => 100,
            'active' => true,
        ];
    }
}
