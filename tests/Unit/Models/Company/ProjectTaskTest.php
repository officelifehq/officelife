<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\ProjectTask;
use App\Models\Company\ProjectTaskList;
use App\Models\Company\TimeTrackingEntry;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $task = ProjectTask::factory()->create();
        $this->assertTrue($task->project()->exists());
    }

    /** @test */
    public function it_has_an_author(): void
    {
        $task = ProjectTask::factory()
            ->for(Employee::factory(), 'author')
            ->create();

        $this->assertTrue($task->author()->exists());
    }

    /** @test */
    public function it_has_a_list(): void
    {
        $task = ProjectTask::factory()
            ->for(ProjectTaskList::factory(), 'list')
            ->create();

        $this->assertTrue($task->list()->exists());
    }

    /** @test */
    public function it_has_an_assignee(): void
    {
        $task = ProjectTask::factory()
            ->for(Employee::factory(), 'assignee')
            ->create();

        $this->assertTrue($task->assignee()->exists());
    }

    /** @test */
    public function it_has_many_time_tracking_entries(): void
    {
        $task = ProjectTask::factory()
            ->has(TimeTrackingEntry::factory(), 'timeTrackingEntries')
            ->create();

        $this->assertTrue($task->timeTrackingEntries()->exists());
    }
}
