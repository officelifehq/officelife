<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Company\Team::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => $faker->name,
        'team_leader_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
    ];
});

$factory->define(App\Models\Company\TeamLog::class, function (Faker $faker) {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create([])->id;
        },
        'action' => 'account_created',
        'author_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\MoraleTeamHistory::class, function () {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create()->id;
        },
        'average' => 2.3,
        'number_of_team_members' => 30,
    ];
});

$factory->define(App\Models\Company\TeamUsefulLink::class, function () {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create()->id;
        },
        'type' => 'slack',
        'label' => '#dunder-mifflin',
        'url' => 'https://slack.com/dunder',
    ];
});

$factory->define(App\Models\Company\TeamNews::class, function () {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create()->id;
        },
        'author_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'author_name' => 'Dwight Schrute',
        'title' => 'Party at the office',
        'content' => 'Michael and Dwight invite you to a party.',
    ];
});
