<?php

namespace Database\Factories\Company;

use App\Models\Company\Meeting;
use App\Models\Company\AgendaItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class AgendaItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AgendaItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $meeting = Meeting::factory()->create();

        return [
            'meeting_id' => $meeting->id,
            'position' => 1,
            'summary' => 'This is the summary',
            'description' => 'This is the description',
        ];
    }
}
