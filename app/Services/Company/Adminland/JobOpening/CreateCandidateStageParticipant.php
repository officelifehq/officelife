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

class CreateCandidateStageParticipant extends BaseService
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
            'participant_id' => 'required|integer|exists:employees,id',
        ];
    }

    /**
     * Add a participant to the candidate stage.
     *
     * @param array $data
     * @return CandidateStageParticipant
     */
    public function execute(array $data): CandidateStageParticipant
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->log();

        return $this->candidateStageParticipant;
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

        $this->participant = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['participant_id']);

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

    private function create(): void
    {
        $this->candidateStageParticipant = CandidateStageParticipant::create([
            'participant_id' => $this->participant->id,
            'candidate_stage_id' => $this->candidateStage->id,
            'participant_name' => $this->participant->name,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'candidate_stage_participant_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
                'job_opening_reference_number' => $this->jobOpening->reference_number,
                'candidate_id' => $this->candidate->id,
                'candidate_name' => $this->candidate->name,
                'participant_id' => $this->participant->id,
                'participant_name' => $this->participant->name,
            ]),
        ])->onQueue('low');
    }
}
