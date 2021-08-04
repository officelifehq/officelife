<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\JobOpening\DestroyCandidateDuringApplicationProcess;

class DestroyCandidateDuringApplicationProcessTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_candidate(): void
    {
        $jobOpening = JobOpening::factory()->create();
        $candidate = Candidate::factory()->create([
            'company_id' => $jobOpening->company_id,
            'job_opening_id' => $jobOpening->id,
        ]);
        $this->executeService($candidate, $jobOpening);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyCandidateDuringApplicationProcess)->execute($request);
    }

    private function executeService(Candidate $candidate, JobOpening $opening): void
    {
        $request = [
            'company_id' => $candidate->company_id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
        ];

        (new DestroyCandidateDuringApplicationProcess)->execute($request);

        $this->assertDatabaseMissing('candidates', [
            'id' => $candidate->id,
            'job_opening_id' => $opening->id,
        ]);
    }
}
