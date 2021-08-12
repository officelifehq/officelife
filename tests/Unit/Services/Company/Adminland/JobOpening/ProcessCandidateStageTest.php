<?php

namespace Tests\Unit\Services\Company\Adminland\JobOpening;

use Tests\TestCase;
use App\Jobs\LogAccountAudit;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\Queue;
use App\Models\Company\CandidateStage;
use App\Models\Company\RecruitingStage;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Company\Adminland\JobOpening\ProcessCandidateStage;

class ProcessCandidateStageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_processes_a_candidate_stage_as_administrator(): void
    {
        Queue::fake();

        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 2,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => true,
        ]);

        $this->assertDatabaseHas('candidate_stages', [
            'id' => $candidateStage->id,
            'candidate_id' => $candidate->id,
            'status' => CandidateStage::STATUS_PASSED,
            'stage_position' => 1,
        ]);

        $this->checkLog($michael, $opening, $candidate, CandidateStage::STATUS_PASSED);
    }

    /** @test */
    public function it_rejects_a_candidate_stage_as_administrator(): void
    {
        Queue::fake();

        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => false,
        ]);

        $this->assertDatabaseHas('candidate_stages', [
            'id' => $candidateStage->id,
            'candidate_id' => $candidate->id,
            'status' => CandidateStage::STATUS_REJECTED,
            'stage_position' => 1,
        ]);

        $this->checkLog($michael, $opening, $candidate, CandidateStage::STATUS_REJECTED);
    }

    /** @test */
    public function it_processes_a_candidate_stage_as_hr(): void
    {
        Queue::fake();

        $michael = $this->createHR();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 2,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => true,
        ]);

        $this->assertDatabaseHas('candidate_stages', [
            'id' => $candidateStage->id,
            'candidate_id' => $candidate->id,
            'status' => CandidateStage::STATUS_PASSED,
            'stage_position' => 1,
        ]);

        $this->checkLog($michael, $opening, $candidate, CandidateStage::STATUS_PASSED);
    }

    /** @test */
    public function it_rejects_a_candidate_stage_as_hr(): void
    {
        Queue::fake();

        $michael = $this->createHR();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => false,
        ]);

        $this->assertDatabaseHas('candidate_stages', [
            'id' => $candidateStage->id,
            'candidate_id' => $candidate->id,
            'status' => CandidateStage::STATUS_REJECTED,
            'stage_position' => 1,
        ]);

        $this->checkLog($michael, $opening, $candidate, CandidateStage::STATUS_REJECTED);
    }

    /** @test */
    public function it_processes_a_candidate_stage_as_sponsor(): void
    {
        Queue::fake();

        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $opening->sponsors()->sync([$michael->id]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);
        CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 2,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => true,
        ]);

        $this->assertDatabaseHas('candidate_stages', [
            'id' => $candidateStage->id,
            'candidate_id' => $candidate->id,
            'status' => CandidateStage::STATUS_PASSED,
            'stage_position' => 1,
        ]);

        $this->checkLog($michael, $opening, $candidate, CandidateStage::STATUS_PASSED);
    }

    /** @test */
    public function it_rejects_a_candidate_stage_as_sponsor(): void
    {
        Queue::fake();

        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        $opening->sponsors()->sync([$michael->id]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => false,
        ]);

        $this->assertDatabaseHas('candidate_stages', [
            'id' => $candidateStage->id,
            'candidate_id' => $candidate->id,
            'status' => CandidateStage::STATUS_REJECTED,
            'stage_position' => 1,
        ]);

        $this->checkLog($michael, $opening, $candidate, CandidateStage::STATUS_REJECTED);
    }

    /** @test */
    public function normal_user_cant_execute_the_service(): void
    {
        Queue::fake();

        $michael = $this->createEmployee();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);

        $this->expectException(ModelNotFoundException::class);
        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => false,
        ]);
    }

    /** @test */
    public function it_fails_if_wrong_parameters_are_given(): void
    {
        $request = [
            'title' => 'Assistant to the regional manager',
        ];

        $this->expectException(ValidationException::class);
        (new ProcessCandidateStage)->execute($request);
    }

    /** @test */
    public function it_fails_if_job_opening_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create();
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'company_id' => $michael->company_id,
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => true,
        ]);
    }

    /** @test */
    public function it_fails_if_candidate_doesnt_belong_to_company(): void
    {
        $this->expectException(ModelNotFoundException::class);

        $michael = $this->createAdministrator();
        $opening = JobOpening::factory()->create([
            'company_id' => $michael->company_id,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 1,
        ]);
        RecruitingStage::factory()->create([
            'recruiting_stage_template_id' => $opening->recruiting_stage_template_id,
            'position' => 2,
        ]);
        $candidate = Candidate::factory()->create([
            'job_opening_id' => $opening->id,
        ]);
        $candidateStage = CandidateStage::factory()->create([
            'candidate_id' => $candidate->id,
            'stage_position' => 1,
        ]);

        (new ProcessCandidateStage)->execute([
            'company_id' => $michael->company_id,
            'author_id' => $michael->id,
            'job_opening_id' => $opening->id,
            'candidate_id' => $candidate->id,
            'candidate_stage_id' => $candidateStage->id,
            'accepted' => true,
        ]);
    }

    private function checkLog(Employee $author, JobOpening $opening, Candidate $candidate, string $status): void
    {
        Queue::assertPushed(LogAccountAudit::class, function ($job) use ($author, $opening, $candidate, $status) {
            return $job->auditLog['action'] === 'candidate_stage_'.$status &&
                $job->auditLog['author_id'] === $author->id &&
                $job->auditLog['objects'] === json_encode([
                    'job_opening_id' => $opening->id,
                    'job_opening_title' => $opening->title,
                    'candidate_id' => $candidate->id,
                    'candidate_name' => $candidate->name,
                ]);
        });
    }
}
