<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Company\Team;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\Employee;
use App\Models\Company\Position;
use App\Models\Company\JobOpening;
use App\Models\Company\RecruitingStageTemplate;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateJobOpening extends BaseService
{
    protected array $data;
    protected Position $position;
    protected JobOpening $jobOpening;
    protected Team $team;

    /**
     * Get the validation rules that apply to the service.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'position_id' => 'required|integer|exists:positions,id',
            'author_id' => 'required|integer|exists:employees,id',
            'sponsors' => 'required|array',
            'team_id' => 'nullable|integer|exists:teams,id',
            'recruiting_stage_template_id' => 'required|integer|exists:recruiting_stage_templates,id',
            'title' => 'required|string|max:255',
            'reference_number' => 'nullable|string|max:255',
            'description' => 'required|string|max:65535',
        ];
    }

    /**
     * Create a job opening.
     *
     * @param array $data
     * @return JobOpening
     */
    public function execute(array $data): JobOpening
    {
        $this->data = $data;

        $this->validate();
        $this->create();
        $this->associateSponsors();
        $this->log();

        return $this->jobOpening;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->position = Position::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['position_id']);

        RecruitingStageTemplate::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['recruiting_stage_template_id']);

        if ($this->data['team_id']) {
            $this->team = Team::where('company_id', $this->data['company_id'])
                ->findOrFail($this->data['team_id']);
        }
    }

    private function create(): void
    {
        $this->jobOpening = JobOpening::create([
            'company_id' => $this->data['company_id'],
            'position_id' => $this->data['position_id'],
            'recruiting_stage_template_id' => $this->data['recruiting_stage_template_id'],
            'team_id' => $this->valueOrNull($this->data, 'team_id'),
            'title' => $this->data['title'],
            'description' => $this->data['description'],
            'reference_number' => $this->valueOrNull($this->data, 'reference_number'),
            'slug' => $this->slug(),
        ]);
    }

    private function associateSponsors(): void
    {
        if (! $this->data['sponsors']) {
            return;
        }

        foreach ($this->data['sponsors'] as &$id) {
            try {
                $sponsor = Employee::where('company_id', $this->data['company_id'])
                    ->findOrFail($id);
            } catch (ModelNotFoundException $e) {
                continue;
            }

            $this->jobOpening->sponsors()->syncWithoutDetaching([
                $sponsor->id,
            ]);
        }
    }

    private function slug(): string
    {
        return Str::slug($this->data['title'], '-').'-'.(string) Str::uuid();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'job_opening_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'job_opening_id' => $this->jobOpening->id,
                'job_opening_title' => $this->jobOpening->title,
            ]),
        ])->onQueue('low');
    }
}
