<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyUsageHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyUsageHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyUsageHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'number_of_active_employees' => 3,
        ];
    }
}
