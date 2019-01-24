<?php

use Faker\Generator as Faker;

$factory->define(App\Models\User\User::class, function (Faker $faker) {
    return [
        'account_id' => factory(App\Models\Account\Account::class)->create()->id,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'remember_token' => str_random(10),
    ];
});
