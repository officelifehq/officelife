<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User\User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'uuid' => $faker->uuid,
    ];
});

$factory->define(App\Models\User\Notification::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(App\Models\User\User::class)->create()->id;
        },
        'action' => 'notification',
        'content' => '{}',
    ];
});
