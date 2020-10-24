<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\ProjectStatus;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProjectStatusTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_project(): void
    {
        $status = factory(ProjectStatus::class)->create([]);
        $this->assertTrue($status->project()->exists());
    }

    /** @test */
    public function it_belongs_to_a_employee(): void
    {
        $status = factory(ProjectStatus::class)->create([]);
        $this->assertTrue($status->author()->exists());
    }
}
