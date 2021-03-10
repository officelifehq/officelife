<?php

namespace Database\Factories\Company;

use App\Models\Company\Company;
use App\Models\Company\MoraleCompanyHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoraleCompanyHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MoraleCompanyHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'average' => 2.3,
            'number_of_employees' => 30,
        ];
    }
}
