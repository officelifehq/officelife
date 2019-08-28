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
        'author_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\EmployeeLog::class, function (Faker $faker) {
    return [
        'employee_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'action' => 'account_created',
        'author_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\TeamLog::class, function (Faker $faker) {
    return [
        'team_id' => function (array $data) {
            return factory(App\Models\Company\Team::class)->create([])->id;
        },
        'action' => 'account_created',
        'author_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
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

$factory->define(App\Models\Company\EmployeeImportantDate::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'occasion' => 'birthdate',
        'date' => '1981-10-29',
    ];
});

$factory->define(App\Models\Company\Morale::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'emotion' => 1,
        'comment' => 'I hate Toby',
    ];
});

$factory->define(App\Models\Company\MoraleCompanyHistory::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'average' => 2.3,
        'number_of_employees' => 30,
    ];
});

$factory->define(App\Models\Company\MoraleTeamHistory::class, function (Faker $faker) {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create()->id;
        },
        'average' => 2.3,
        'number_of_team_members' => 30,
    ];
});

$factory->define(App\Models\Company\CompanyNews::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'author_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'author_name' => 'Dwight Schrute',
        'title' => 'Party at the office',
        'content' => 'Michael and Dwight invite you to a party.',
    ];
});
