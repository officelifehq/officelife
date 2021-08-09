<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use Illuminate\Support\Facades\DB;
use App\Models\Company\CandidateStage;

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
            $this->log(CandidateStage::STATUS_PASSED);
        } else {
            $this->markAsRejected();
            $this->log(CandidateStage::STATUS_REJECTED);
        }

        return $this->candidate;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->jobOpening = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);

        $this->candidate = Candidate::where('company_id', $this->data['company_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->findOrFail($this->data['candidate_id']);

        // check if the author is a sponsor
        $isSponsor = DB::table('job_opening_sponsor')
            ->where('employee_id', $this->data['author_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->exists();

        if (! $isSponsor) {
            $this->author = Employee::where('company_id', $this->data['company_id'])
                ->where('permission_level', '<=', config('officelife.permission_level.hr'))
                ->findOrFail($this->data['author_id']);
        }
    }

    private function goToNextStage(): void
    {
        // how many stages does the job opening have?
        $jobOpeningStages = $this->jobOpening->template->stages()->get();

        // is the candidate already in the recruiting process?
        $candidateStage = $this->candidate->stages()->orderBy('stage_position', 'desc')->first();

        if ($candidateStage) {
            $this->updateCurrentCandidateStage($candidateStage, CandidateStage::STATUS_PASSED);

            $remainingStages = $jobOpeningStages->filter(function ($stage) use ($candidateStage) {
                return $stage->position > $candidateStage->stage_position;
            });

            if ($remainingStages->count() > 0) {
                $newPosition = $remainingStages->first()->position;
                $this->createCandidateStage($newPosition);
            } else {
                $newPosition = 0;
            }
        } else {
            $this->createCandidateStage(1);
        }
    }

    private function createCandidateStage($position): void
    {
        CandidateStage::create([
            'candidate_id' => $this->candidate->id,
            'stage_position' => $position,
            'status' => CandidateStage::STATUS_PENDING,
        ]);
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
        // is the candidate already in the recruiting process?
        $candidateStage = $this->candidate->stages()->orderBy('stage_position', 'desc')->first();

        if ($candidateStage) {
            $this->updateCurrentCandidateStage($candidateStage, CandidateStage::STATUS_REJECTED);
        }

        $this->candidate->rejected = true;
        $this->candidate->save();
    }

    private function log(string $status): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'candidate_stage_'.$status,
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
                'candidate_id' => $this->candidate->id,
                'candidate_name' => $this->candidate->name,
            ]),
        ])->onQueue('low');
    }
}
