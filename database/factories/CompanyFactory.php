<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Company\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(App\Models\Company\Employee::class, function (Faker $faker) {
    return [
        'user_id' => factory(App\Models\User\User::class)->create()->id,
        'company_id' => factory(App\Models\Company\Company::class)->create()->id,
        'uuid' => $faker->uuid,
        'permission_level' => config('homas.authorizations.administrator'),
    ];
});

$factory->define(App\Models\Company\Team::class, function (Faker $faker) {
    return [
        'company_id' => factory(App\Models\Company\Company::class)->create()->id,
        'name' => $faker->name,
    ];
});

$factory->define(App\Models\Company\AuditLog::class, function (Faker $faker) {
    return [
        'company_id' => factory(App\Models\Company\Company::class)->create()->id,
        'action' => 'account_created',
        'objects' => '{"user": 1}',
    ];
});
