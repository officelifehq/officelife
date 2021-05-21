<?php

namespace Database\Factories\Company;

use App\Models\Company\Group;
use App\Models\Company\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

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
            'name' => 'Group name',
            'mission' => 'Employees happiness',
        ];
    }
}
