<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\JobOpening;
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
    public function it_belongs_to_a_sponsor(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $this->assertTrue($jobOpening->sponsor()->exists());
    }

    /** @test */
    public function it_belongs_to_a_team(): void
    {
        $jobOpening = JobOpening::factory()->create([]);
        $this->assertTrue($jobOpening->team()->exists());
    }
}
