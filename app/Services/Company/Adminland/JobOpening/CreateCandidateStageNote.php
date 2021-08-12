<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\RecruitingStage;
use App\Models\Company\RecruitingStageTemplate;

class CreateCandidateStageNote extends BaseService
{
    protected array $data;
    protected RecruitingStage $recruitingStage;
    protected int $startPosition;
    protected int $endPosition;

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
            'candidate_stage_id' => 'required|integer|exists:candidate_stages,id',
            'note' => 'required|string|max:65535',
        ];
    }

    /**
     * Write a note for the given candidate stage.
     *
     * @param array $data
     * @return Candid
     */
    public function execute(array $data): RecruitingStage
    {
        $this->data = $data;
        $this->validate();
        $this->update();
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

        $this->recruitingStage = RecruitingStage::where('recruiting_stage_template_id', $this->data['recruiting_stage_template_id'])
            ->findOrFail($this->data['recruiting_stage_id']);
    }

    private function update(): void
    {
        $this->recruitingStage->name = $this->data['name'];
        $this->recruitingStage->position = $this->endPosition;
        $this->recruitingStage->save();

        $this->recruitingStage->refresh();
    }

    private function updateAllOtherPositions(): void
    {
        RecruitingStage::where('recruiting_stage_template_id', $this->data['recruiting_stage_template_id'])
            ->where('position', '>', $this->startPosition)
            ->where('position', '<=', $this->endPosition)
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
            'action' => 'recruiting_stage_updated',
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
