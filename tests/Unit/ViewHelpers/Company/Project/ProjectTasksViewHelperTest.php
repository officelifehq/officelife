<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\ImageHelper;
use App\Models\Company\Project;
use App\Models\Company\Timesheet;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectTaskList;
use App\Models\Company\TimeTrackingEntry;
use GrahamCampbell\TestBenchCore\HelperTrait;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Http\ViewHelpers\Company\Project\ProjectTasksViewHelper;

class ProjectTasksViewHelperTest extends TestCase
{
    use DatabaseTransactions, HelperTrait;

    /** @test */
    public function it_gets_a_collection_of_tasks_without_task_lists(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskA = ProjectTask::factory()->completed()->create([
            'project_task_list_id' => null,
            'project_id' => $project->id,
            'author_id' => $michael->id,
            'assignee_id' => $michael->id,
        ]);
        $projectTaskB = ProjectTask::factory()->create([
            'project_task_list_id' => null,
            'project_id' => $project->id,
            'author_id' => null,
        ]);

        $array = ProjectTasksViewHelper::index($project);

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectTaskA->id,
                    'title' => $projectTaskA->title,
                    'description' => $projectTaskA->description,
                    'completed' => true,
                    'duration' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskA->id,
                    'assignee' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 15),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                ],
                1 => [
                    'id' => $projectTaskB->id,
                    'title' => $projectTaskB->title,
                    'description' => $projectTaskB->description,
                    'completed' => false,
                    'duration' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskB->id,
                    'assignee' => null,
                ],
            ],
            $array['tasks_without_lists']->toArray()
        );
    }

    /** @test */
    public function it_gets_a_collection_of_tasks_with_task_lists(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskListA = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskListB = ProjectTaskList::factory()->create([
            'project_id' => $project->id,
        ]);
        $projectTaskA = ProjectTask::factory()->completed()->create([
            'project_id' => $project->id,
            'project_task_list_id' => $projectTaskListA->id,
            'author_id' => $michael->id,
            'assignee_id' => $michael->id,
        ]);
        $projectTaskB = ProjectTask::factory()->create([
            'project_id' => $project->id,
            'project_task_list_id' => $projectTaskListB->id,
            'author_id' => null,
        ]);
        $projectTaskC = ProjectTask::factory()->create([
            'project_id' => $project->id,
            'project_task_list_id' => null,
            'author_id' => null,
        ]);

        $array = ProjectTasksViewHelper::index($project);

        // project task list A
        $this->assertEquals(
            $projectTaskListA->id,
            $array['task_lists'][0]['id']
        );
        $this->assertEquals(
            $projectTaskListA->title,
            $array['task_lists'][0]['title']
        );
        $this->assertEquals(
            $projectTaskListA->description,
            $array['task_lists'][0]['description']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $projectTaskA->id,
                    'title' => $projectTaskA->title,
                    'description' => $projectTaskA->description,
                    'completed' => true,
                    'duration' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskA->id,
                    'assignee' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'avatar' => ImageHelper::getAvatar($michael, 15),
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                ],
            ],
            $array['task_lists'][0]['tasks']->toArray()
        );

        // project task list B
        $this->assertEquals(
            $projectTaskListB->id,
            $array['task_lists'][1]['id']
        );
        $this->assertEquals(
            $projectTaskListB->title,
            $array['task_lists'][1]['title']
        );
        $this->assertEquals(
            $projectTaskListB->description,
            $array['task_lists'][1]['description']
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectTaskB->id,
                    'title' => $projectTaskB->title,
                    'description' => $projectTaskB->description,
                    'completed' => false,
                    'duration' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskB->id,
                    'assignee' => null,
                ],
            ],
            $array['task_lists'][1]['tasks']->toArray()
        );

        $this->assertEquals(
            [
                0 => [
                    'id' => $projectTaskC->id,
                    'title' => $projectTaskC->title,
                    'description' => $projectTaskC->description,
                    'completed' => false,
                    'duration' => null,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskC->id,
                    'assignee' => null,
                ],
            ],
            $array['tasks_without_lists']->toArray()
        );
    }

    /** @test */
    public function it_gets_the_details_of_a_single_task(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTaskA = ProjectTask::factory()->completed()->create([
            'project_id' => $project->id,
            'author_id' => $michael->id,
            'assignee_id' => $michael->id,
        ]);

        $array = ProjectTasksViewHelper::getTaskFullDetails($projectTaskA, $michael->company, $michael);

        $this->assertEquals(
            [
                'id' => $projectTaskA->id,
                'title' => $projectTaskA->title,
                'description' => $projectTaskA->description,
                'completed' => true,
                'completed_at' => 'Jan 01, 2018',
                'created_at' => 'Jan 01, 2018',
                'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskA->id,
                'duration' => null,
                'author' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 35),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    'role' => null,
                    'added_at' => null,
                    'position' => $michael->position->title,
                ],
                'assignee' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 35),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array
        );
    }

    /** @test */
    public function it_gets_a_collection_of_all_employees_in_the_project(): void
    {
        $michael = $this->createAdministrator();
        $dwight = $this->createAnotherEmployee($michael);
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $project->employees()->attach([$michael->id]);
        $project->employees()->attach([$dwight->id]);

        $response = ProjectTasksViewHelper::members($project);

        $this->assertCount(
            2,
            $response
        );

        $this->assertArraySubset(
            [
                'value' => $michael->id,
                'label' => $michael->name,
            ],
            $response->toArray()[0]
        );
    }

    /** @test */
    public function it_gets_an_array_containing_the_task_details(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->completed()->create([
            'project_id' => $project->id,
            'author_id' => $michael->id,
            'assignee_id' => $michael->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
        ]);
        $entry = TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheet->id,
            'employee_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $projectTask->id,
            'happened_at' => Carbon::now()->startOfWeek(),
            'duration' => 100,
        ]);

        $array = ProjectTasksViewHelper::taskDetails($projectTask, $michael->company, $michael);

        $this->assertEquals(
            [
                'id' => $projectTask->id,
                'title' => $projectTask->title,
                'description' => $projectTask->description,
                'completed' => true,
                'completed_at' => 'Jan 01, 2018',
                'created_at' => 'Jan 01, 2018',
                'duration' => '01h40',
                'url' => env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTask->id,
                'author' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 35),
                    'role' => null,
                    'added_at' => null,
                    'position' => $michael->position->title,
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
                'assignee' => [
                    'id' => $michael->id,
                    'name' => $michael->name,
                    'avatar' => ImageHelper::getAvatar($michael, 35),
                    'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                ],
            ],
            $array['task']
        );

        $this->assertEquals(
            [
                'name' => $projectTask->list->title,
            ],
            $array['list']
        );
    }

    /** @test */
    public function it_gets_an_array_containing_the_time_tracking_entries_information(): void
    {
        Carbon::setTestNow(Carbon::create(2018, 1, 1));

        $michael = $this->createAdministrator();
        $project = Project::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $projectTask = ProjectTask::factory()->completed()->create([
            'project_id' => $project->id,
            'author_id' => $michael->id,
            'assignee_id' => $michael->id,
        ]);
        $timesheet = Timesheet::factory()->create([
            'company_id' => $michael->company_id,
            'employee_id' => $michael->id,
            'started_at' => Carbon::now()->startOfWeek(),
            'ended_at' => Carbon::now()->endOfWeek(),
        ]);
        $entry = TimeTrackingEntry::factory()->create([
            'timesheet_id' => $timesheet->id,
            'employee_id' => $michael->id,
            'project_id' => $project->id,
            'project_task_id' => $projectTask->id,
            'happened_at' => Carbon::now()->startOfWeek(),
            'duration' => 100,
        ]);

        $collection = ProjectTasksViewHelper::timeTrackingEntries($projectTask, $michael->company, $michael);

        $this->assertEquals(
            [
                0 => [
                    'id' => $entry->id,
                    'duration' => '01h40',
                    'created_at' => 'Jan 01, 2018',
                    'employee' => [
                        'id' => $michael->id,
                        'name' => $michael->name,
                        'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                    ],
                ],
            ],
            $collection->toArray()
        );
    }
}
