<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpeningStage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CandidateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $candidate = Candidate::factory()->create();
        $this->assertTrue($candidate->company()->exists());
    }

    /** @test */
    public function it_has_many_files(): void
    {
        $candidate = Candidate::factory()->create();

        $file = File::factory()->create();
        $candidate->files()->sync([$file->id]);

        $this->assertTrue($candidate->files()->exists());
    }

    /** @test */
    public function it_belongs_to_a_job_opening(): void
    {
        $candidate = Candidate::factory()->create();
        $this->assertTrue($candidate->jobOpening()->exists());
    }

    /** @test */
    public function it_gets_the_current_job_opening_stage(): void
    {
        $candidate = Candidate::factory()->create();

        $this->assertNull($candidate->getCurrentJobOpeningRecruitingStage());

        $stage = JobOpeningStage::factory()->create();
        $candidate->highest_reached_stage_id = $stage->id;
        $candidate->save();

        $this->assertEquals(
            $stage->id,
            $candidate->getCurrentJobOpeningRecruitingStage()->id
        );
    }
}
