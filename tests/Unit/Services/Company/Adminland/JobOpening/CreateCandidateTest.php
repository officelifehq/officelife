<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\Company\Adminland\JobOpening\CreateCandidate;

class CreateCandidateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_candidate(): void
    {
        $jobOpening = JobOpening::factory()->create();
        $this->executeService($jobOpening);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new CreateCandidate)->execute($request);
    }

    private function executeService(JobOpening $jobOpening): void
    {
        $request = [
            'job_opening_id' => $jobOpening->id,
            'name' => 'Regis',
            'email' => 'regis@gmail.com',
            'desired_salary' => 10000,
        ];

        $candidate = (new CreateCandidate)->execute($request);

        $this->assertDatabaseHas('candidates', [
            'id' => $candidate->id,
            'job_opening_id' => $jobOpening->id,
            'name' => 'Regis',
            'email' => 'regis@gmail.com',
            'desired_salary' => 10000,
            'url' => null,
        ]);

        $this->assertInstanceOf(
            Candidate::class,
            $candidate
        );
    }
}
