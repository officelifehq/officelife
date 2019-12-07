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
        'pronoun_id' => function () {
            return factory(App\Models\User\Pronoun::class)->create()->id;
        },
        'uuid' => $faker->uuid,
        'avatar' => 'https://api.adorable.io/avatars/285/abott@adorable.png',
        'permission_level' => config('officelife.authorizations.administrator'),
        'email' => 'dwigth@dundermifflin.com',
        'first_name' => 'Dwight',
        'last_name' => 'Schrute',
        'birthdate' => $faker->dateTimeThisCentury()->format('Y-m-d H:i:s'),
        'consecutive_worklog_missed' => 0,
        'employee_status_id' => function (array $data) {
            return factory(App\Models\Company\EmployeeStatus::class)->create([
                'company_id' => $data['company_id'],
            ])->id;
        },
        'amount_of_allowed_holidays' => 30,
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
        'objects' => '{"user": 1}',
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

$factory->define(App\Models\Company\Country::class, function (Faker $faker) {
    return [
        'name' => 'France',
    ];
});

$factory->define(App\Models\Company\Place::class, function (Faker $faker) {
    return [
        'street' => '1725 Slough Ave',
        'city' => 'Scranton',
        'province' => 'PA',
        'postal_code' => '',
        'country_id' => function () {
            return factory(App\Models\Company\Country::class)->create()->id;
        },
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'placable_id' => function (array $data) {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'placable_id' => 'App\Models\Company\Employee',
    ];
});

$factory->define(App\Models\Company\CompanyPTOPolicy::class, function (Faker $faker) {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'year' => 2020,
        'total_worked_days' => 250,
        'default_amount_of_allowed_holidays' => 30,
        'default_amount_of_sick_days' => 3,
        'default_amount_of_pto_days' => 5,
    ];
});

$factory->define(App\Models\Company\EmployeeDailyCalendarEntry::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'new_balance' => 10,
        'daily_accrued_amount' => 1,
        'current_holidays_per_year' => 100,
        'default_amount_of_allowed_holidays_in_company' => 100,
        'on_holiday' => false,
        'sick_day' => false,
        'pto_day' => false,
        'remote' => false,
        'log_date' => '2010-01-01',
    ];
});

$factory->define(App\Models\Company\EmployeePlannedHoliday::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'planned_date' => '2010-01-01',
        'full' => true,
        'actually_taken' => false,
        'type' => 'holiday',
    ];
});

$factory->define(App\Models\Company\CompanyCalendar::class, function (Faker $faker) {
    return [
        'company_pto_policy_id' => function () {
            return factory(App\Models\Company\CompanyPTOPolicy::class)->create()->id;
        },
        'day' => '2010-01-01',
        'day_of_year' => 1,
        'day_of_week' => 1,
        'is_worked' => true,
    ];
});

$factory->define(App\Models\Company\Company::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
    ];
});
