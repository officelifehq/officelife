<?php

namespace Database\Factories\Company;

use App\Models\Company\Team;
use App\Models\Company\TeamLog;
use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'action' => 'account_created',
            'author_id' => Employee::factory(),
            'author_name' => $this->faker->name,
            'audited_at' => $this->faker->dateTimeThisCentury(),
            'objects' => '{"user": 1}',
        ];
    }
}
