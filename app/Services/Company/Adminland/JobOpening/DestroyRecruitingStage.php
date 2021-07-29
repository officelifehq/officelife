<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\RecruitingStage;
use App\Models\Company\RecruitingStageTemplate;

class DestroyRecruitingStage extends BaseService
{
    protected array $data;
    protected RecruitingStage $recruitingStage;
    private RecruitingStageTemplate $template;
    protected int $startPosition;

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
            'recruiting_stage_id' => 'required|integer|exists:recruiting_stages,id',
        ];
    }

    /**
     * Delete a recruiting stage.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
        $this->updateAllOtherPositions();
        $this->log();
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

        $this->recruitingStage = RecruitingStage::where('recruiting_stage_template_id', $this->data['recruiting_stage_template_id'])
            ->findOrFail($this->data['recruiting_stage_id']);

        $this->startPosition = $this->recruitingStage->position;
    }

    private function destroy(): void
    {
        $this->recruitingStage->delete();
    }

    private function updateAllOtherPositions(): void
    {
        RecruitingStage::where('recruiting_stage_template_id', $this->data['recruiting_stage_template_id'])
            ->where('position', '>', $this->startPosition)
            ->get()
            ->each(function ($recruitingStage) {
                $recruitingStage->position = $recruitingStage->position - 1;
                $recruitingStage->save();
            });
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'recruiting_stage_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'recruiting_stage_name' => $this->recruitingStage->name,
            ]),
        ])->onQueue('low');
    }
}
