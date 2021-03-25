<?php

namespace Database\Factories\Company;

use Carbon\Carbon;
use App\Models\Company\Group;
use App\Models\Company\Meeting;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meeting::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_id' => Group::factory(),
        ];
    }

    /**
     * Indicate that the meeting has happened.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function happened()
    {
        return $this->state(function (array $attributes) {
            return [
                'happened' => true,
                'happened_at' => Carbon::now(),
            ];
        });
    }
}
