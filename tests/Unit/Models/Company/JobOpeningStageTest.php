<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\JobOpeningStage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobOpeningStageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_job_opening(): void
    {
        $stage = JobOpeningStage::factory()->create([]);
        $this->assertTrue($stage->jobOpening()->exists());
    }
}
