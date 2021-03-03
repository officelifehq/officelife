<?php

namespace App\Services\Company\Adminland\Company;

use App\Services\BaseService;
use App\Models\Company\Company;

class DestroyCompany extends BaseService
{
    protected Company $company;

    protected array $data;

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
        ];
    }

    /**
     * Destroy the company.
     *
     * @param array $data
     */
    public function execute(array $data): void
    {
        $this->data = $data;
        $this->validate();

        $this->data = $data;
        $this->destroy();
    }

    private function validate(): void
    {
        $this->validateRules($this->data);

        $this->author($this->data['author_id'])
            ->inCompany($this->data['company_id'])
            ->asAtLeastAdministrator()
            ->canExecuteService();

        $this->company = Company::find($this->data['company_id']);
    }

    private function destroy(): void
    {
        $this->company->delete();
    }
}
