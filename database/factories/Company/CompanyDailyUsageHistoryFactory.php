<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\CompanyDailyUsageHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyDailyUsageHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyDailyUsageHistory::class;

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
