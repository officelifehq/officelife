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
use App\Exceptions\NotEnoughPermissionException;
use App\Models\Company\CandidateStageParticipant;

class DestroyCandidateStageParticipant extends BaseService
{
    protected array $data;
    protected JobOpening $jobOpening;
    protected Candidate $candidate;
    protected Employee $participant;
    protected CandidateStage $candidateStage;
    protected CandidateStageParticipant $candidateStageParticipant;

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
            'candidate_stage_participant_id' => 'required|integer|exists:candidate_stage_participants,id',
        ];
    }

    /**
     * Remove a participant from the candidate stage.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->remove();
        $this->log();
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

        $this->candidateStageParticipant = CandidateStageParticipant::where('candidate_stage_id', $this->candidateStage->id)
            ->findOrFail($this->data['candidate_stage_participant_id']);

        // check if the author is a sponsor
        $isSponsor = DB::table('job_opening_sponsor')
            ->where('employee_id', $this->data['author_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->exists();

        $this->author = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['author_id']);

        if (! $isSponsor) {
            try {
                $this->author = Employee::where('company_id', $this->data['company_id'])
                    ->where('permission_level', '<=', config('officelife.permission_level.hr'))
                    ->findOrFail($this->data['author_id']);
            } catch (NotEnoughPermissionException $e) {
                throw new NotEnoughPermissionException;
            }
        }
    }

    private function remove(): void
    {
        $this->candidateStageParticipant->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'candidate_stage_participant_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
                'job_opening_reference_number' => $this->jobOpening->reference_number,
                'participant_id' => $this->candidateStageParticipant->participant->id,
                'participant_name' => $this->candidateStageParticipant->participant->name,
            ]),
        ])->onQueue('low');
    }
}
