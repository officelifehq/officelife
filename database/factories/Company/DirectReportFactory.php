<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Employee;
use App\Models\Company\DirectReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class DirectReportFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DirectReport::class;

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
            'manager_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
            'employee_id' => Employee::factory()->create([
                'company_id' => $company->id,
            ]),
        ];
    }
}
