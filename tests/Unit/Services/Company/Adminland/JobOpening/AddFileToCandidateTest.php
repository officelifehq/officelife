<?php

namespace App\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Candidate;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddFileToCandidateTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_file_to_a_candidate(): void
    {
        $file = File::factory()->create();
        $candidate = Candidate::factory()->create([
            'company_id' => $file->company_id,
        ]);
        $this->executeService($file, $candidate);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new AddFileToCandidate)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_file_is_not_in_the_authors_company(): void
    {
        $file = File::factory()->create();
        $candidate = Candidate::factory()->create();
        $this->expectException(ModelNotFoundException::class);
        $this->executeService($file, $candidate);
    }

    private function executeService(File $file, Candidate $candidate): void
    {
        Queue::fake();

        $request = [
            'company_id' => $file->company_id,
            'candidate_id' => $candidate->id,
            'file_id' => $file->id,
        ];

        $file = (new AddFileToCandidate)->execute($request);

        $this->assertDatabaseHas('candidate_file', [
            'candidate_id' => $candidate->id,
            'file_id' => $file->id,
        ]);

        $this->assertInstanceOf(
            File::class,
            $file
        );
    }
}
