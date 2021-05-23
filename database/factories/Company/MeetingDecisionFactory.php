<?php

namespace Database\Factories\Company;

use App\Models\Company\AgendaItem;
use App\Models\Company\MeetingDecision;
use Illuminate\Database\Eloquent\Factories\Factory;

class MeetingDecisionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MeetingDecision::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $agenda = AgendaItem::factory()->create([]);

        return [
            'agenda_item_id' => $agenda->id,
            'description' => 'This is a description',
        ];
    }
}
