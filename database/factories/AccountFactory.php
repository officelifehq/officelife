<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Account\Account::class, function (Faker $faker) {
    return [
        'subdomain' => $faker->name,
    ];
});

$factory->define(App\Models\Account\Team::class, function (Faker $faker) {
    return [
        'account_id' => factory(App\Models\Account\Account::class)->create()->id,
        'name' => $faker->name,
        'description' => $faker->paragraph,
    ];
});
