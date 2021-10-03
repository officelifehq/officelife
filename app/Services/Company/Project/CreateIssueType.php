<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\IssueType;

class CreateIssueType extends BaseService
{
    protected array $data;
    protected IssueType $type;

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
     * Create an issue type.
     *
     * @param array $data
     * @return IssueType
     */
    public function execute(array $data): IssueType
    {
        $this->data = $data;
        $this->validate();
        $this->create();
        $this->log();

        return $this->type;
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
        $this->type = IssueType::create([
            'company_id' => $this->data['company_id'],
            'name' => $this->data['name'],
        ]);
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'project_board_created',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'project_id' => $this->project->id,
                'project_name' => $this->project->name,
                'name' => $this->projectBoard->name,
            ]),
        ])->onQueue('low');
    }
}
