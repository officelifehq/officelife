<?php

namespace Tests\Unit\Models\Company;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Candidate;
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
}
