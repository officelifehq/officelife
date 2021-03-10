<?php

namespace Database\Factories\Company;

use App\Models\Company\Team;
use App\Models\Company\TeamUsefulLink;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamUsefulLinkFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TeamUsefulLink::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'team_id' => Team::factory(),
            'type' => 'slack',
            'label' => '#dunder-mifflin',
            'url' => 'https://slack.com/dunder',
        ];
    }
}
