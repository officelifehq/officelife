<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\RecruitingStageTemplate;

class DestroyRecruitingStageTemplate extends BaseService
{
    protected array $data;
    protected RecruitingStageTemplate $template;
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
        ];
    }

    /**
     * Delete a recruiting stage template.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->destroy();
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
    }

    private function destroy(): void
    {
        $this->template->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'recruiting_stage_template_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'recruiting_stage_template_name' => $this->template->name,
            ]),
        ])->onQueue('low');
    }
}
