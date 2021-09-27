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
use App\Models\Company\CandidateStageNote;

class CreateCandidateStageNote extends BaseService
{
    protected array $data;
    protected JobOpening $jobOpening;
    protected Candidate $candidate;
    protected CandidateStage $candidateStage;
    protected CandidateStageNote $candidateStageNote;
    protected bool $isParticipant;

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
            'note' => 'required|string|max:65535',
        ];
    }

    /**
     * Write a note for the given candidate stage.
     *
     * @param array $data
     * @return CandidateStageNote
     */
    public function execute(array $data): CandidateStageNote
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->markParticipated();
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

        // check if the author is a sponsor
        $isSponsor = DB::table('job_opening_sponsor')
            ->where('employee_id', $this->data['author_id'])
            ->where('job_opening_id', $this->data['job_opening_id'])
            ->exists();

        // check if the author is a participant of the recruiting process
        $this->isParticipant = DB::table('candidate_stage_participants')
            ->where('participant_id', $this->data['author_id'])
            ->where('candidate_stage_id', $this->data['candidate_stage_id'])
            ->exists();

        $this->author = Employee::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['author_id']);

        if (! $isSponsor && ! $this->isParticipant) {
            $this->author = Employee::where('company_id', $this->data['company_id'])
                ->where('permission_level', '<=', config('officelife.permission_level.hr'))
                ->findOrFail($this->data['author_id']);
        }
    }

    private function create(): void
    {
        $this->candidateStageNote = CandidateStageNote::create([
            'author_id' => $this->author->id,
            'candidate_stage_id' => $this->candidateStage->id,
            'note' => $this->data['note'],
            'author_name' => $this->author->name,
        ]);
    }

    private function markParticipated(): void
    {
        if ($this->isParticipant) {
            DB::table('candidate_stage_participants')
                ->where('participant_id', $this->data['author_id'])
                ->where('candidate_stage_id', $this->data['candidate_stage_id'])
                ->update([
                    'participated' => true,
                    'participated_at' => Carbon::now(),
                ]);
        }
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'candidate_stage_note_created',
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
