<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CandidateStage;
use App\Models\Company\CandidateStageNote;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\DestroyCandidateStageNote;

class DestroyCandidateStageNoteTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_deletes_a_candidate_stage_note_as_administrator(): void
    {
        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $candidateStage->id,
        ]);

        $this->executeService($michael, $opening, $candidate, $candidateStage, $candidateStageNote);
    }

    /** @test */
    public function it_deletes_a_candidate_stage_note_as_hr(): void
    {
        $michael = $this->createHR();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $candidateStage->id,
        ]);

        $this->executeService($michael, $opening, $candidate, $candidateStage, $candidateStageNote);
    }

    /** @test */
    public function it_deletes_a_candidate_stage_note_as_author(): void
    {
        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $opening->sponsors()->sync([$michael->id]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $candidateStage->id,
            'author_id' => $michael->id,
        ]);

        $this->executeService($michael, $opening, $candidate, $candidateStage, $candidateStageNote);
    }

    /** @test */
    public function it_cant_delete_a_candidate_stage_note_as_sponsor(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $candidateStage->id,
        ]);

        $this->executeService($michael, $opening, $candidate, $candidateStage, $candidateStageNote);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $candidateStage->id,
        ]);

        $this->executeService($michael, $opening, $candidate, $candidateStage, $candidateStageNote);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new DestroyCandidateStageNote)->execute($request);
    }

    /** @test */
    public function it_fails_if_job_opening_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        $candidateStageNote = CandidateStageNote::factory()->create([
            'candidate_stage_id' => $candidateStage->id,
        ]);

        $this->executeService($michael, $opening, $candidate, $candidateStage, $candidateStageNote);
    }

    private function executeService(Employee $author, JobOpening $opening, Candidate $candidate, CandidateStage $candidateStage, CandidateStageNote $note): void
    {
        Queue::fake();

        (new DestroyCandidateStageNote)->execute([
            'company_id' => $author->company_id,
            'author_id' => $author->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'candidate_stage_note_id' => $note->id,
        ]);

        $this->assertDatabaseMissing('candidate_stage_notes', [
            'id' => $note->id,
        ]);

        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $opening, $candidate) {
            return $job->auditLog['action'] === 'candidate_stage_note_destroyed' &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'job_opening_id' => $opening->id,
                    'job_opening_title' => $opening->title,
                    'job_opening_reference_number' => $opening->reference_number,
                    'candidate_id' => $candidate->id,
                    'candidate_name' => $candidate->name,
                ]);
        });
    }
}
