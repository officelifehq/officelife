<?php

namespace App\Services\Company\Adminland\JobOpening;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\RecruitingStageTemplate;

class CreateRecruitingStageTemplate extends BaseService
{
    private array $data;
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
            'name' => 'required|string|max:255',
        ];
    }

    /**
     * Create a recruiting stage template.
     *
     * @param array $data
     * @return RecruitingStageTemplate
     */
    public function execute(array $data): RecruitingStageTemplate
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->log();

        return $this->template;
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();
    }

    private function create(): void
    {
        $this->template = RecruitingStageTemplate::create([
            'company_id' => $this->data['company_id'],
            'name' => $this->data['name'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'recruiting_stage_template_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'recruiting_stage_template_id' => $this->template->id,
                'recruiting_stage_template_name' => $this->template->name,
            ]),
        ])->onQueue('low');
    }
}
