<?php

namespace Tests\Unit\ViewHelpers\Company\Project;

use Carbon\Carbon;
use Tests\TestCase;
use App\Helpers\DateHelper;
use App\Helpers\ImageHelper;
use App\Helpers\StringHelper;
use App\Models\Company\Comment;
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
                    'comment_count' => 0,
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
                    'comment_count' => 0,
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
                    'comment_count' => 0,
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
                    'comment_count' => 0,
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
                    'comment_count' => 0,
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

        $comment = Comment::factory()->create();
        $projectTaskA->comments()->save($comment);

        $array = ProjectTasksViewHelper::getTaskFullDetails($projectTaskA, $michael->company, $michael);

        $this->assertEquals(
            $projectTaskA->id,
            $array['id']
        );
        $this->assertEquals(
            $projectTaskA->title,
            $array['title']
        );
        $this->assertEquals(
            $projectTaskA->description,
            $array['description']
        );
        $this->assertEquals(
            true,
            $array['completed']
        );
        $this->assertEquals(
            'Jan 01, 2018',
            $array['completed_at']
        );
        $this->assertEquals(
            'Jan 01, 2018',
            $array['created_at']
        );
        $this->assertEquals(
            env('APP_URL').'/'.$michael->company_id.'/company/projects/'.$project->id.'/tasks/'.$projectTaskA->id,
            $array['url']
        );
        $this->assertEquals(
            null,
            $array['duration']
        );
        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => ImageHelper::getAvatar($michael, 35),
                'url' => env('APP_URL').'/'.$michael->company_id.'/employees/'.$michael->id,
                'role' => null,
                'added_at' => null,
                'position' => $michael->position->title,
            ],
            $array['author']
        );
        $this->assertEquals(
            [
                'id' => $michael->id,
                'name' => $michael->name,
                'avatar' => ImageHelper::getAvatar($michael, 35),
                'url' => env('APP_URL') . '/' . $michael->company_id . '/employees/' . $michael->id,
            ],
            $array['assignee']
        );
        $this->assertEquals(
            [
                0 => [
                    'id' => $comment->id,
                    'content' => StringHelper::parse($comment->content),
                    'content_raw' => $comment->content,
                    'written_at' => DateHelper::formatShortDateWithTime($comment->created_at),
                    'author' => [
                        'id' => $comment->author->id,
                        'name' => $comment->author->name,
                        'avatar' => ImageHelper::getAvatar($comment->author, 32),
                        'url' => route('employees.show', [
                            'company' => $michael->company_id,
                            'employee' => $comment->author,
                        ]),
                    ],
                    'can_edit' => true,
                    'can_delete' => true,
                ],
            ],
            $array['comments']->toArray()
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

    /** @test */
    public function it_gets_a_collection_of_task_lists(): void
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

        $collection = ProjectTasksViewHelper::taskLists($project);

        $this->assertEquals(
            [
                0 => [
                    'value' => $projectTaskListA->id,
                    'label' => $projectTaskListA->title,
                ],
                1 => [
                    'value' => $projectTaskListB->id,
                    'label' => $projectTaskListB->title,
                ],
            ],
            $collection->toArray()
        );
    }
}
