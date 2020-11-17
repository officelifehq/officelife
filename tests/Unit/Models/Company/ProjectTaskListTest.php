<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectTaskList;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTaskListTest extends TestCase
{
    use DatabaseTransactions;

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
