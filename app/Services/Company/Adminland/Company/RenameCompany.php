<?php

namespace App\Services\Company\Adminland\Company;

use App\Jobs\LogAccountAudit;
use App\Models\Company\Company;
use App\Services\BaseService;
use Carbon\Carbon;

class RenameCompany extends BaseService
{
    protected Company $company;

    protected array $data;

    /**
     * Get the validation rules that apply to the service.
     */
    public function rules(): array
    {
        return [
            'company_id' => 'required|integer|exists:companies,id',
            'author_id' => 'required|integer|exists:employees,id',
            'name' => 'required|unique:companies,name|string|max:255',
        ];
    }

    /**
     * Rename the company.
     */
    public function execute(array $data): Company
    {
        $this->validateRules($data);

        $this->author($data['author_id'])
            ->inCompany($data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $this->data = $data;

        $this->company = Company::find($data['company_id']);
        $oldName = $this->company->name;

        $this->rename();

        $this->generateSlug();

        $this->log($oldName);

        return $this->company;
    }

    private function rename(): void
    {
        Company::where('id', $this->company->id)->update([
            'name' => $this->data['name'],
        ]);
    }

    private function generateSlug(): void
    {
        (new UpdateCompanySlug)->execute([
            'company_id' => $this->company->id,
        ]);
    }

    /**
     * Add an audit log entry for this action.
     */
    private function log(string $oldName): void
    {
        LogAccountAudit::dispatch([
            'company_id' => $this->company->id,
            'action' => 'company_renamed',
            'author_id' => $this->author->id,
            'author_name' => $this->author->name,
            'audited_at' => Carbon::now(),
            'objects' => json_encode([
                'old_name' => $oldName,
                'new_name' => $this->data['name'],
            ]),
        ])->onQueue('low');
    }
}
