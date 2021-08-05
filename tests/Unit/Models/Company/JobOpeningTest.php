<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use App\Models\Company\JobOpeningStage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class JobOpeningTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_belongs_to_a_company(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $this->assertTrue($jobOpening->company()->exists());
    }

    /** @test */
    public function it_belongs_to_a_position(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $this->assertTrue($jobOpening->position()->exists());
    }

    /** @test */
    public function it_belongs_to_multiple_sponsors(): void
    {
        $jobOpening = JobOpening::factory()->create();
        $dwight = Employee::factory()->create();
        $jobOpening->sponsors()->sync([$dwight->id]);
        $this->assertTrue($jobOpening->sponsors()->exists());
    }

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $this->assertTrue($jobOpening->team()->exists());
    }

    /** @test */
    public function it_belongs_to_a_recruiting_stage_template(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $this->assertTrue($jobOpening->template()->exists());
    }

    /** @test */
    public function it_has_many_candidates(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        Candidate::factory()->count(2)->create([
            'job_opening_id' => $jobOpening->id,
        ]);

        $this->assertTrue($jobOpening->candidates()->exists());
    }

    /** @test */
    public function it_has_many_stages(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        JobOpeningStage::factory()->count(2)->create([
            'job_opening_id' => $jobOpening->id,
        ]);
        $this->assertTrue($jobOpening->stages()->exists());
    }
}
