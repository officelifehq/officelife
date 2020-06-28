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
        'permission_level' => config('officelife.permission_level.administrator'),
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
        'author_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([]);
        },
        'author_name' => 'Dwight Schrute',
        'audited_at' => $faker->dateTimeThisCentury(),
        'objects' => '{"user": 1}',
    ];
});

$factory->define(App\Models\Company\EmployeeLog::class, function (Faker $faker) {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
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

$factory->define(App\Models\Company\DirectReport::class, function () {
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

$factory->define(App\Models\Company\Position::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'Assistant to the regional manager',
    ];
});

$factory->define(App\Models\Company\Flow::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'Birthdate',
        'type' => 'employee_joins_company',
    ];
});

$factory->define(App\Models\Company\Step::class, function () {
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

$factory->define(App\Models\Company\Action::class, function () {
    return [
        'step_id' => function () {
            return factory(App\Models\Company\Step::class)->create()->id;
        },
        'type' => 'notification',
        'recipient' => 'manager',
        'specific_recipient_information' => null,
    ];
});

$factory->define(App\Models\Company\Task::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'Welcome the new employee',
    ];
});

$factory->define(App\Models\Company\Notification::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'action' => 'notification',
        'objects' => '{"user": 1}',
        'read' => false,
    ];
});

$factory->define(App\Models\Company\Worklog::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'content' => 'This is what I have done',
    ];
});

$factory->define(App\Models\Company\EmployeeStatus::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'Permanent',
    ];
});

$factory->define(App\Models\Company\Morale::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'emotion' => 1,
        'comment' => 'I hate Toby',
    ];
});

$factory->define(App\Models\Company\MoraleCompanyHistory::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'average' => 2.3,
        'number_of_employees' => 30,
    ];
});

$factory->define(App\Models\Company\CompanyNews::class, function () {
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

$factory->define(App\Models\Company\Country::class, function () {
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
        'placable_id' => function () {
            return factory(App\Models\Company\Employee::class)->create([])->id;
        },
        'placable_type' => 'App\Models\Company\Employee',
    ];
});

$factory->define(App\Models\Company\CompanyPTOPolicy::class, function () {
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

$factory->define(App\Models\Company\EmployeeDailyCalendarEntry::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'new_balance' => 10,
        'daily_accrued_amount' => 1,
        'current_holidays_per_year' => 100,
        'default_amount_of_allowed_holidays_in_company' => 100,
        'log_date' => '2010-01-01',
    ];
});

$factory->define(App\Models\Company\EmployeePlannedHoliday::class, function () {
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

$factory->define(App\Models\Company\CompanyCalendar::class, function () {
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

$factory->define(App\Models\Company\WorkFromHome::class, function () {
    return [
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'date' => '2010-01-01',
        'work_from_home' => true,
    ];
});

$factory->define(App\Models\Company\Question::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'title' => 'What is your favorite movie?',
        'active' => true,
    ];
});

$factory->define(App\Models\Company\Answer::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'question_id' => function () use ($companyId) {
            return factory(App\Models\Company\Question::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'employee_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'body' => 'This is my answer',
    ];
});

$factory->define(App\Models\Company\Hardware::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'iPhone',
        'serial_number' => '123',
    ];
});

$factory->define(App\Models\Company\Ship::class, function () {
    return [
        'team_id' => function () {
            return factory(App\Models\Company\Team::class)->create()->id;
        },
        'title' => 'New API',
    ];
});

$factory->define(App\Models\Company\Skill::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'PHP',
    ];
});

$factory->define(App\Models\Company\ExpenseCategory::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'travel',
    ];
});
