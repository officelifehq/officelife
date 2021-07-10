<?php

namespace Database\Factories\User;

use App\Models\User\User;
use App\Models\User\UserToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'driver' => 'github',
            'driver_id' => $this->faker->uuid,
            'format' => 'oauth2',
            'token' => $this->faker->word,
        ];
    }
}
