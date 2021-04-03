<?php

namespace Database\Factories\Company;

use App\Models\Company\OneOnOneNote;
use App\Models\Company\OneOnOneEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class OneOnOneNoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OneOnOneNote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'one_on_one_entry_id' => OneOnOneEntry::factory(),
            'note' => $this->faker->sentence(),
        ];
    }
}
