<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectTask;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectTaskTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $task = ProjectTask::factory()->make();
        $this->assertTrue($task->project()->exists());
    }
}
