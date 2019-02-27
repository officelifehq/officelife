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
        'avatar' => 'https://api.adorable.io/avatars/285/abott@adorable.png',
        'permission_level' => config('homas.authorizations.administrator'),
        'email' => 'dwigth@dundermifflin.com',
        'first_name' => 'Dwight',
        'last_name' => 'Schrute',
        'birthdate' => '1978-01-20',
    ];
});

$factory->define(App\Models\Company\Team::class, function (Faker $faker) {
    return [
        'company_id' => factory(App\Models\Company\Company::class)->create()->id,
        'name' => $faker->name,
        'team_leader_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
    ];
});

$factory->define(App\Models\Company\AuditLog::class, function (Faker $faker) {
    return [
        'company_id' => factory(App\Models\Company\Company::class)->create()->id,
        'action' => 'account_created',
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\DirectReport::class, function (Faker $faker) {
    return [
        'company_id' => factory(App\Models\Company\Company::class)->create()->id,
        'manager_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'employee_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
    ];
});
