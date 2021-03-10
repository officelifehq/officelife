<?php

namespace Database\Factories\Company;

use App\Models\Company\Step;
use App\Models\Company\Action;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Action::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'step_id' => Step::factory(),
            'type' => 'notification',
            'recipient' => 'manager',
            'specific_recipient_information' => null,
        ];
    }
}
