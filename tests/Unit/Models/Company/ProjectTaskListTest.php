<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ProjectTaskList;
use Tests\TestCase;

class ProjectTaskListTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $taskList = ProjectTaskList::factory()->create();
        $this->assertTrue($taskList->project()->exists());
    }

    /** @test */
    public function it_has_one_author(): void
    {
        $taskList = ProjectTaskList::factory()->create();
        $this->assertTrue($taskList->author()->exists());
    }
}
