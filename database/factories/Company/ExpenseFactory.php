<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\Expense;
use App\Models\Company\Employee;
use App\Models\Company\ExpenseCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expense::class;

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
            'expense_category_id' => ExpenseCategory::factory()->create([
                'company_id' => $company->id,
            ]),
            'status' => 'created',
            'title' => $this->faker->name,
            'amount' => '100',
            'currency' => 'USD',
            'expensed_at' => '1999-01-01',
        ];
    }
}
