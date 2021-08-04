<?php

namespace App\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Models\Company\File;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DestroyCandidateFileDuringApplicationProcessTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_removes_a_file_from_a_candidate(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate->files()->syncWithoutDetaching([
            $file->id,
        ]);
        $this->executeService($michael, $file, $candidate);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'first_name' => 'Dwight',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyCandidateFileDuringApplicationProcess)->execute($request);
    }

    /** @test */
    public function it_fails_if_the_candidate_is_not_in_the_company(): void
    {
        $michael = $this->createAdministrator();
        $file = File::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create();
        $candidate->files()->syncWithoutDetaching([
            $file->id,
        ]);

        $this->expectException(ModelNotFoundException::class);
        $this->executeService($michael, $file, $candidate);
    }

    private function executeService(Employee $michael, File $file, Candidate $candidate): void
    {
        Event::fake();

        $request = [
            'company_id' => $michael->company_id,
            'candidate_id' => $candidate->id,
            'file_id' => $file->id,
        ];

        (new DestroyCandidateFileDuringApplicationProcess)->execute($request);

        $this->assertDatabaseMissing('candidate_file', [
            'candidate_id' => $candidate->id,
            'file_id' => $file->id,
        ]);

        $this->assertDatabaseMissing('files', [
            'id' => $file->id,
        ]);
    }
}
