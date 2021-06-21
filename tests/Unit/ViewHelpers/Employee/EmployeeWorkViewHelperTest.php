<?php

namespace Tests\Unit\ViewHelpers\Employee;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Group;
use App\Models\Company\Morale;
use App\Models\Company\Project;
use App\Models\Company\Worklog;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectMessage;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Employee\EmployeeWorkViewHelper;

class EmployeeWorkViewHelperTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_the_details_of_a_worklog(): void
    {
        $date = Carbon::create(2018, 1, 1);
        Carbon::setTestNow($date);
        $startOfWeek = $date->copy()->startOfWeek();

        $michael = $this->createAdministrator();
        $worklog = new Worklog();

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->startOfWeek()->addDays($i);

            $worklog = Worklog::factory()->create([
                'employee_id' => $michael->id,
                'content' => 'test',
                'created_at' => $day,
            ]);
            Morale::factory()->create([
                'employee_id' => $michael->id,
                'created_at' => $day,
            ]);
        }

        $array = EmployeeWorkViewHelper::worklog($michael, $michael, $startOfWeek, $date->copy()->addDays(6));

        $this->assertEquals(7, count($array['days']->toArray()));

        $this->assertEquals(
            '2018-01-01',
            $array['current_week']
        );

        $this->assertEquals(
            $worklog->id,
            $array['id']
        );

        $this->assertEquals(
            '<p>test</p>',
            $array['worklog_parsed_content']
        );

        $this->assertEquals(
            '😡 I’ve had a bad day',
            $array['morale']
        );

        $this->assertEquals(
            7,
            $array['days']->count()
        );
    }

    /** @test */
    public function it_gets_the_different_weeks(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));
        $michael = $this->createAdministrator();

        $collection = EmployeeWorkViewHelper::weeks($michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => 1,
                    'label' => '3 weeks ago',
                    'range' => [
                        'start' => 'December 11th',
                        'end' => 'December 17th',
                    ],
                    'start_of_week_date' => '2017-12-11',
                ],
                1 => [
                    'id' => 2,
                    'label' => '2 weeks ago',
                    'range' => [
                        'start' => 'December 18th',
                        'end' => 'December 24th',
                    ],
                    'start_of_week_date' => '2017-12-18',
                ],
                2 => [
                    'id' => 3,
                    'label' => 'Last week',
                    'range' => [
                        'start' => 'December 25th',
                        'end' => 'December 31st',
                    ],
                    'start_of_week_date' => '2017-12-25',
                ],
                3 => [
                    'id' => 4,
                    'label' => 'Current week',
                    'range' => [
                        'start' => 'January 1st',
                        'end' => 'January 7th',
                    ],
                    'start_of_week_date' => '2018-01-01',
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_all_projects_for_this_employee(): void
    {
        $michael = $this->createAdministrator();
        $projectA = Project::factory()->create([
            'company_id' => $michael->company_id,
            'status' => Project::CLOSED,
        ]);
        $projectB = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectA->employees()->syncWithoutDetaching(
            [
                $michael->id => [
                    'role' => trans('project.project_title_lead'),
                ],
            ]
        );
        $projectB->employees()->syncWithoutDetaching([$michael->id]);

        ProjectMessage::factory()->create([
            'project_id' => $projectA->id,
            'author_id' => $michael->id,
        ]);
        ProjectMessage::factory()->create([
            'project_id' => $projectA->id,
            'author_id' => null,
        ]);

        ProjectTask::factory()->count(2)->completed()->create([
            'project_id' => $projectA->id,
            'author_id' => $michael->id,
            'assignee_id' => $michael->id,
        ]);

        $collection = EmployeeWorkViewHelper::projects($michael, $michael->company);

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectB->id,
                    'name' => $projectB->name,
                    'code' => $projectB->code,
                    'status' => $projectB->status,
                    'role' => null,
                    'messages_count' => 0,
                    'tasks_count' => 0,
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/company/projects/' . $projectB->id,
                ],
                1 => [
                    'id' => $projectA->id,
                    'name' => $projectA->name,
                    'code' => $projectA->code,
                    'status' => Project::CLOSED,
                    'role' => trans('project.project_title_lead'),
                    'messages_count' => 1,
                    'tasks_count' => 2,
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/company/projects/' . $projectA->id,
                ],
            ],
            $collection->toArray()
        );
    }

    /** @test */
    public function it_gets_a_list_of_groups(): void
    {
        Carbon::setTestNow(Carbon::create(2019, 1, 1, 7, 0, 0));

        $michael = $this->createAdministrator();
        $group = Group::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $group->employees()->attach([$michael->id]);

        $collection = EmployeeWorkViewHelper::groups($michael, $michael->company);

        $this->assertEquals(
            $group->id,
            $collection->toArray()[0]['id']
        );

        $this->assertEquals(
            $group->name,
            $collection->toArray()[0]['name']
        );

        $this->assertEquals(
            '<p>Employees happiness</p>',
            $collection->toArray()[0]['mission']
        );

        $this->assertEquals(
            env('APP_URL') . '/' . $michael->company_id . '/company/groups/' . $group->id,
            $collection->toArray()[0]['url']
        );

        $this->assertEquals(
            0,
            $collection->toArray()[0]['remaining_members_count']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $michael->id,
                    'avatar' => ImageHelper::getAvatar($michael, 25),
                    'url' => env('APP_URL') . '/' . $michael->company_id . '/employees/' . $michael->id,
                ],
            ],
            $collection->toArray()[0]['preview_members']->toArray()
        );
    }
}
