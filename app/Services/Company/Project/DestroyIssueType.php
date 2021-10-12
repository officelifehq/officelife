<?php

namespace App\Services\Company\Project;

use Carbon\Carbon;
use App\Jobs\LogAccountAudit;
use App\Services\BaseService;
use App\Models\Company\IssueType;

class DestroyIssueType extends BaseService
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
            'issue_type_id' => 'required|integer|exists:issue_types,id',
        ];
    }

    /**
     * Delete an issue type.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();
        $this->delete();
        $this->log();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastHR()
            ->canExecuteService();

        $this->type = IssueType::where('company_id', $this->data['company_id'])
            ->findOrFail($this->data['issue_type_id']);
    }

    private function delete(): void
    {
        $this->type->delete();
    }

    private function log(): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->data['company_id'],
            'action' => 'issue_type_destroyed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'issue_type_name' => $this->type->name,
            ]),
        ])->onQueue('low');
    }
}
