<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Candidate;
use App\Models\Company\JobOpening;
use App\Models\Company\CandidateStage;
use App\Models\Company\CandidateStageNote;
use App\Exceptions\NotEnoughPermissionException;

class UpdateCandidateStageNote extends BaseService
{
    protected array $data;
    protected JobOpening $jobOpening;
    protected Candidate $candidate;
    protected Employee $participant;
    protected CandidateStage $candidateStage;
    protected CandidateStageNote $candidateStageNote;

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
            'candidate_stage_id' => 'required|integer|exists:candidate_stages,id',
            'candidate_stage_note_id' => 'required|integer|exists:candidate_stage_notes,id',
            'note' => 'required|string|max:65535',
        ];
    }

    /**
     * Update a note from the candidate stage.
     *
     * @param array $data
     * @return CandidateStageNote
     */
    public function execute(array $data): CandidateStageNote
    {
        $this->data = $data;
        $this->validate();
        $this->edit();
        $this->log();

        return $this->candidateStageNote;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->jobOpening = JobOpening::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['job_opening_id']);

        $this->candidate = Candidate::where('company_id', $this->data['company_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->findOrFail($this->data['candidate_id']);

        $this->candidateStage = CandidateStage::where('candidate_id', $this->data['candidate_id'])
            ->findOrFail($this->data['candidate_stage_id']);

        $this->candidateStageNote = CandidateStageNote::where('candidate_stage_id', $this->candidateStage->id)
            ->findOrFail($this->data['candidate_stage_note_id']);

        // check if the author is a participant of the recruiting process
        $this->author = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['author_id']);

        $isAuthor = $this->candidateStageNote->author->id === $this->author->id;

        if (! $isAuthor) {
            try {
                $this->author = Employee::where('company_id', $this->data['company_id'])
                    ->where('permission_level', '<=', config('officelife.permission_level.hr'))
                    ->findOrFail($this->data['author_id']);
            } catch (NotEnoughPermissionException $e) {
                throw new NotEnoughPermissionException;
            }
        }
    }

    private function edit(): void
    {
        $this->candidateStageNote->note = $this->data['note'];
        $this->candidateStageNote->save();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'candidate_stage_note_updated',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
                'job_opening_reference_number' => $this->jobOpening->reference_number,
                'candidate_id' => $this->candidate->id,
                'candidate_name' => $this->candidate->name,
            ]),
        ])->onQueue('low');
    }
}
