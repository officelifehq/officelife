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
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'position_id' => function (array $data) {
            return factory(App\Models\Company\Position::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'uuid' => $faker->uuid,
        'avatar' => 'https://api.adorable.io/avatars/285/abott@adorable.png',
        'permission_level' => config('homas.authorizations.administrator'),
        'email' => 'dwigth@dundermifflin.com',
        'first_name' => 'Dwight',
        'last_name' => 'Schrute',
        'birthdate' => '1978-01-20',
        'consecutive_worklog_missed' => 0,
        'employee_status_id' => function (array $data) {
            return factory(App\Models\Company\EmployeeStatus::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
    ];
});

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

$factory->define(App\Models\Company\AuditLog::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'action' => 'account_created',
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\EmployeeLog::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'employee_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'action' => 'account_created',
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\TeamLog::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'team_id' => function (array $data) {
            return factory(App\Models\Company\Team::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'action' => 'account_created',
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\DirectReport::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
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

$factory->define(App\Models\Company\Position::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'Assistant to the regional manager',
    ];
});

$factory->define(App\Models\Company\EmployeeEvent::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'employee_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'label' => 'Birthday',
        'date' => '1981-10-29',
    ];
});

$factory->define(App\Models\Company\Flow::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'Birthdate',
        'type' => 'employee_joins_company',
    ];
});

$factory->define(App\Models\Company\Step::class, function (Faker $faker) {
    return [
        'flow_id' => function () {
            return factory(App\Models\Company\Flow::class)->create()->id;
        },
        'number' => 3,
        'unit_of_time' => 'days',
        'modifier' => 'after',
        'real_number_of_days' => 3,
    ];
});

$factory->define(App\Models\Company\Action::class, function (Faker $faker) {
    return [
        'step_id' => function () {
            return factory(App\Models\Company\Step::class)->create()->id;
        },
        'type' => 'notification',
        'recipient' => 'manager',
        'specific_recipient_information' => null,
    ];
});

$factory->define(App\Models\Company\Task::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'Welcome the new employee',
    ];
});

$factory->define(App\Models\Company\Notification::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'action' => 'notification',
        'objects' => '{}',
        'read' => false,
    ];
});

$factory->define(App\Models\Company\Worklog::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'content' => 'This is what I have done',
    ];
});

$factory->define(App\Models\Company\EmployeeStatus::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'Permanent',
    ];
});
