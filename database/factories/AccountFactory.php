<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Account\Account::class, function (Faker $faker) {
    return [
        'subdomain' => $faker->name,
    ];
});
