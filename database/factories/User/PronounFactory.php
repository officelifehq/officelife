<?php

namespace Database\Factories\User;

use App\Models\User\Pronoun;
use Illuminate\Database\Eloquent\Factories\Factory;

class PronounFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Pronoun::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'label' => 'he/him',
            'translation_key' => 'account.pronoun_he_him',
        ];
    }
}
