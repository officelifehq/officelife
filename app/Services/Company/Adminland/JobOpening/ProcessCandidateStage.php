<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CandidateStage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProcessCandidateStage extends BaseService
{
    protected array $data;
    protected JobOpening $jobOpening;
    protected Candidate $candidate;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'job_opening_id' => 'required|integer|exists:job_openings,id',
            'candidate_id' => 'required|integer|exists:candidates,id',
            'accepted' => 'required|boolean',
        ];
    }

    /**
     * Process the stage for the given candidate.
     * The candidate can either pass it, or fail it.
     * --> If it passes, the candidate goes to the next stage, if it exists. If it
     * doesn't exist, the candidate has won the job opening.
     * --> If it fails, the candidate is marked as rejected.
     *
     * @param array $data
     * @return Candidate
     */
    public function execute(array $data): Candidate
    {
        $this->data = $data;

        $this->validate();
        if ($this->data['accepted']) {
            $this->goToNextStage();
        } else {
            $this->markAsRejected();
        }

        return $this->candidate;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->jobOpening = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);

        $this->candidate = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);

        // check if the author is a sponsor
        $isSponsor = DB::table('job_opening_sponsor')
            ->where('employee_id', $this->data['author_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->exists();

        if (! $isSponsor) {
            $this->author = Employee::where('company_id', $this->data['company_id'])
                ->where('permission_level', '<=', config('officelife.permission_level.hr'))
                ->firstOrFail();
        }
    }

    private function goToNextStage(): void
    {
        $currentJobOpeningStageForCandidate = $this->candidate->getCurrentJobOpeningRecruitingStage();

        // if stage exists, mark stage as passed
        if ($currentJobOpeningStageForCandidate) {
            try {
                $candidateStage = CandidateStage::where('candidate_id', $this->candidate->id)
                    ->where('job_opening_stage_id', $currentJobOpeningStageForCandidate->id)
                    ->firstOrFail();

                $this->updateCurrentCandidateStage($candidateStage, CandidateStage::STATUS_PASSED);
            } catch (ModelNotFoundException $e) {
            }
        }

        // check which stage is the next one
        $allJobOpeningStages = $this->jobOpening->stages()
            ->orderBy('recruiting_stage_id', 'asc')
            ->get();

        // if the candidate was already in a stage, we have to find the
        // next stage
        if ($currentJobOpeningStageForCandidate) {
            $remainingStages = $allJobOpeningStages->filter(function ($stage) use ($currentJobOpeningStageForCandidate) {
                return $stage->id > $currentJobOpeningStageForCandidate->id;
            });

            // if there is no other stage, there is nothing to do
            // if there is another stage, we should create a new CandidateStage
            // entry for the candidate
            if ($remainingStages->count() != 0) {
                CandidateStage::create([
                    'candidate_id' => $this->candidate->id,
                    'job_opening_stage_id' => $this->jobOpening->id,
                    'status' => CandidateStage::STATUS_PENDING,
                ]);
            }
        }
    }

    private function updateCurrentCandidateStage(CandidateStage $stage, string $status): void
    {
        $stage->status = $status;
        $stage->decider_id = $this->author->id;
        $stage->decider_name = $this->author->name;
        $stage->decided_at = Carbon::now();
        $stage->save();
    }

    private function markAsRejected(): void
    {
    }
}
