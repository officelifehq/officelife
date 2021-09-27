<?php

namespace Database\Factories\Company;

use App\Models\Company\CompanyDailyUsageHistory;
use App\Models\Company\CompanyUsageHistoryDetails;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyUsageHistoryDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyUsageHistoryDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'usage_history_id' => CompanyDailyUsageHistory::factory(),
            'employee_name' => $this->faker->name,
            'employee_email' => $this->faker->email,
        ];
    }
}
