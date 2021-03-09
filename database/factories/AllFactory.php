<?php

use Carbon\Carbon;
use App\Models\Company\Team;
use Faker\Generator as Faker;
use App\Models\Company\Project;
use App\Models\Company\ProjectStatus;

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
        'employee_id' => function () {
            return factory(App\Models\Company\Employee::class)->create()->id;
        },
        'title' => 'Welcome the new employee',
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
        'team_id' => Team::factory()->create()->id,
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

$factory->define(App\Models\Company\OneOnOneTalkingPoint::class, function () {
    return [
        'one_on_one_entry_id' => function () {
            return factory(App\Models\Company\OneOnOneEntry::class)->create([])->id;
        },
        'description' => 'what are you doing right now',
        'checked' => false,
    ];
});

$factory->define(App\Models\Company\OneOnOneActionItem::class, function () {
    return [
        'one_on_one_entry_id' => function () {
            return factory(App\Models\Company\OneOnOneEntry::class)->create([])->id;
        },
        'description' => 'what are you doing right now',
        'checked' => false,
    ];
});

$factory->define(App\Models\Company\OneOnOneNote::class, function () {
    return [
        'one_on_one_entry_id' => function () {
            return factory(App\Models\Company\OneOnOneEntry::class)->create([])->id;
        },
        'note' => 'what are you doing right now',
    ];
});

$factory->define(App\Models\Company\Project::class, function () {
    return [
        'company_id' => function () {
            return factory(App\Models\Company\Company::class)->create()->id;
        },
        'name' => 'API v3',
        'code' => '123456',
        'description' => 'it is going well',
        'status' => Project::CREATED,
    ];
});

$factory->define(App\Models\Company\ProjectLink::class, function () {
    return [
        'project_id' => function () {
            return factory(App\Models\Company\Project::class)->create()->id;
        },
        'type' => 'slack',
        'label' => '#dunder-mifflin',
        'url' => 'https://slack.com/dunder',
    ];
});

$factory->define(App\Models\Company\ProjectStatus::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'project_id' => function () use ($companyId) {
            return factory(App\Models\Company\Project::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'author_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'status' => ProjectStatus::ON_TRACK,
        'title' => 'Title',
        'description' => 'it is going well',
    ];
});

$factory->define(App\Models\Company\ProjectDecision::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'project_id' => function () use ($companyId) {
            return factory(App\Models\Company\Project::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'author_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'title' => 'This is a title',
        'decided_at' => Carbon::now(),
    ];
});

$factory->define(App\Models\Company\ProjectMessage::class, function () {
    $companyId = factory(App\Models\Company\Company::class)->create()->id;

    return [
        'project_id' => function () use ($companyId) {
            return factory(App\Models\Company\Project::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'author_id' => function () use ($companyId) {
            return factory(App\Models\Company\Employee::class)->create([
                'company_id' => $companyId,
            ])->id;
        },
        'title' => 'This is a title',
        'content' => 'This is a description',
    ];
});
