<?php

namespace Database\Factories\Company;

use App\Models\Company\CompanyCalendar;
use App\Models\Company\CompanyPTOPolicy;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyCalendarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CompanyCalendar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_pto_policy_id' => CompanyPTOPolicy::factory(),
            'day' => '2010-01-01',
            'day_of_year' => 1,
            'day_of_week' => 1,
            'is_worked' => true,
        ];
    }
}
