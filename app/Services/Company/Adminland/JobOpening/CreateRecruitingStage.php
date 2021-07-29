<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\RecruitingStage;
use App\Models\Company\RecruitingStageTemplate;

class CreateRecruitingStage extends BaseService
{
    private array $data;
    private int $position;
    private RecruitingStage $recruitingStage;
    private RecruitingStageTemplate $template;

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
            'recruiting_stage_template_id' => 'required|integer|exists:recruiting_stage_templates,id',
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Create a recruiting stage.
     *
     * @param array $data
     * @return RecruitingStage
     */
    public function execute(array $data): RecruitingStage
    {
        $this->data = $data;
        $this->validate();
        $this->determineNextPosition();
        $this->create();
        $this->log();

        return $this->recruitingStage;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->template = RecruitingStageTemplate::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['recruiting_stage_template_id']);
    }

    private function determineNextPosition(): void
    {
        $highestPosition = RecruitingStage::where('recruiting_stage_template_id', $this->data['recruiting_stage_template_id'])
            ->max('position');

        $this->position = $highestPosition + 1;
    }

    private function create(): void
    {
        $this->recruitingStage = RecruitingStage::create([
            'recruiting_stage_template_id' => $this->template->id,
            'name' => $this->data['name'],
            'position' => $this->position,
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'recruiting_stage_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'recruiting_stage_id' => $this->recruitingStage->id,
                'recruiting_stage_name' => $this->recruitingStage->name,
            ]),
        ])->onQueue('low');
    }
}
