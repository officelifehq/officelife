<?php

namespace Tests\Unit\Models\Company;

use App\Models\Company\ProjectStatus;
use Tests\TestCase;

class ProjectStatusTest extends TestCase
{
    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $status = ProjectStatus::factory()->create([]);
        $this->assertTrue($status->project()->exists());
    }

    /** @test */
    public function it_belongs_to_a_employee(): void
    {
        $status = ProjectStatus::factory()->create([]);
        $this->assertTrue($status->author()->exists());
    }
}
