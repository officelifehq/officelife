<?php

namespace Database\Factories\Company;

use App\Models\Company\Team;
use App\Models\Company\MoraleTeamHistory;
use Illuminate\Database\Eloquent\Factories\Factory;

class MoraleTeamHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MoraleTeamHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'average' => 2.3,
            'number_of_team_members' => 30,
        ];
    }
}
